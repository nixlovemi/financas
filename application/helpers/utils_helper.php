<?php
/**
* valida e retorna as variaveis
* $return->nome, por exemplo
*/
function proccessPost(){
  $postdata = file_get_contents("php://input");
  if($postdata != ""){
    $jsonVars  = json_decode($postdata);
  } else {
    $jsonStr  = json_encode($_REQUEST);
    $jsonVars = json_decode($jsonStr);
  }
  if(!isset($jsonVars->appkey) || $jsonVars->appkey != "9837f6f54e56b471aaa046192d488587"){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["error"] = true;
    $arrRet["msg"]  = "Key de acesso inválida!" ;
    echo json_encode($arrRet);
    die();
  } else {
    return $jsonVars;
  }
}

/**
* encoda uma string para passar como parametro
*/
function base64url_encode($data)
{
    // return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    return base64_encode(urlencode($data));
}

/**
* decoda uma string para passar como parametro
*/
function base64url_decode($data)
{
    // return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    return urldecode(base64_decode($data));
}

/**
* retorna o HTML do bloco de totais
*/
function getHtmlBlocoTotais($label, $value, $colorClass="bg_lb"){
  $vLabel = mb_strtoupper($label);

  return "<ul class='quick-actions'>
            <li class='$colorClass' style='width: 100%;'>
              <a href='index.html'>
                <i class='icon icon-tasks'></i>
                <span style='font-size: 18px;' class=''>$value</span>
                <br />$vLabel
              </a>
            </li>
          </ul>";
}

function getUserIP(){
  $client  = @$_SERVER['HTTP_CLIENT_IP'];
  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $remote  = $_SERVER['REMOTE_ADDR'];

  if (filter_var($client, FILTER_VALIDATE_IP)) {
    $ip = $client;
  } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    $ip = $forward;
  } else {
    $ip = $remote;
  }

  return $ip;
}

/*
* passa string com 12 digitos e ele calcula DV e retorna 13 caracteres
*/
function generateEAN($number)
{
  $code = $number;

  if(strlen($code) != 12){
    return false;
  }

  $weightflag = true;
  $sum = 0;
  // Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit.
  // loop backwards to make the loop length-agnostic. The same basic functionality
  // will work for codes of different lengths.
  for ($i = strlen($code) - 1; $i >= 0; $i--)
  {
    $sum += (int)$code[$i] * ($weightflag?3:1);
    $weightflag = !$weightflag;
  }
  $code .= (10 - ($sum % 10)) % 10;
  return $code;
}

/**
* Gera um ID único e inteiro
*
* @return int (negativo)
*/
function getRandomID(){
  $usuId  = (isset($_SESSION["id"])) ? $_SESSION["id"] : 61;
  $random = (rand(1, 2147483).$usuId) * -1;
  return substr($random, 0, 10);
}

/**
* Formata textos que serão salvos na base de dados e NÃO
* foram enviados através de uma VIEW.
*
* Essa função deve ser utilizada APENAS em CONTROLLERS
*
* @param string $texto
* @return string
*/
function formatarTextoFixoController($texto)
{
  return utf8_decode($texto);
}

/**
* Faz a validacao do CPF
* @param text $CampoNumero (sem a pontuacao)
* @return boolean
*/
function is_cpf($CampoNumero)
{
  $RecebeCPF = $CampoNumero;
  // Retirar todos os caracteres que nao sejam 0-9
  $s         = "";
  for ($x = 1; $x <= strlen($RecebeCPF); $x = $x + 1) {
    $ch = substr($RecebeCPF, $x - 1, 1);
    if (ord($ch) >= 48 && ord($ch) <= 57) {
      $s = $s.$ch;
    }
  }
  $RecebeCPF = $s;
  if (strlen($RecebeCPF) != 11) {
    return false;
  } else if ($RecebeCPF == "00000000000" || $RecebeCPF == "11111111111" || $RecebeCPF
  == "22222222222" || $RecebeCPF == "33333333333" || $RecebeCPF == "44444444444"
  || $RecebeCPF == "55555555555" || $RecebeCPF == "66666666666" || $RecebeCPF
  == "77777777777" || $RecebeCPF == "88888888888" || $RecebeCPF == "99999999999") {
    return false;
  } else {
    $Numero [1]  = intval(substr($RecebeCPF, 1 - 1, 1));
    $Numero [2]  = intval(substr($RecebeCPF, 2 - 1, 1));
    $Numero [3]  = intval(substr($RecebeCPF, 3 - 1, 1));
    $Numero [4]  = intval(substr($RecebeCPF, 4 - 1, 1));
    $Numero [5]  = intval(substr($RecebeCPF, 5 - 1, 1));
    $Numero [6]  = intval(substr($RecebeCPF, 6 - 1, 1));
    $Numero [7]  = intval(substr($RecebeCPF, 7 - 1, 1));
    $Numero [8]  = intval(substr($RecebeCPF, 8 - 1, 1));
    $Numero [9]  = intval(substr($RecebeCPF, 9 - 1, 1));
    $Numero [10] = intval(substr($RecebeCPF, 10 - 1, 1));
    $Numero [11] = intval(substr($RecebeCPF, 11 - 1, 1));
    $soma        = 10 * $Numero [1] + 9 * $Numero [2] + 8 * $Numero [3] + 7
    * $Numero [4] + 6 * $Numero [5] + 5 * $Numero [6] + 4 * $Numero [7]
    + 3 * $Numero [8] + 2 * $Numero [9];
    $soma        = $soma - (11 * (intval($soma / 11)));
    if ($soma == 0 || $soma == 1) {
      $resultado1 = 0;
    } else {
      $resultado1 = 11 - $soma;
    }
    if ($resultado1 == $Numero [10]) {
      $soma = $Numero [1] * 11 + $Numero [2] * 10 + $Numero [3] * 9 + $Numero [4]
      * 8 + $Numero [5] * 7 + $Numero [6] * 6 + $Numero [7] * 5 + $Numero [8]
      * 4 + $Numero [9] * 3 + $Numero [10] * 2;
      $soma = $soma - (11 * (intval($soma / 11)));
      if ($soma == 0 || $soma == 1) {
        $resultado2 = 0;
      } else {
        $resultado2 = 11 - $soma;
      }
      if ($resultado2 == $Numero [11]) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}

/**
* Faz a validacao do CNPJ
* @param text $CampoNumero
* @return boolean
*/
function is_cnpj($CampoNumero)
{
  $CampoNumero = str_replace(array(".", "/", "-"), "", $CampoNumero);
  $RecebeCNPJ  = ${"CampoNumero"};
  $s           = "";
  for ($x = 1; $x <= strlen($RecebeCNPJ); $x = $x + 1) {
    $ch = substr($RecebeCNPJ, $x - 1, 1);
    if (ord($ch) >= 48 && ord($ch) <= 57) {
      $s = $s.$ch;
    }
  }
  $RecebeCNPJ = $s;
  if (strlen($RecebeCNPJ) != 14) {
    return false;
  } else if ($RecebeCNPJ == "00000000000000" || $RecebeCNPJ == "11111111111111"
  || $RecebeCNPJ == "22222222222222" || $RecebeCNPJ == "33333333333333"
  || $RecebeCNPJ == "44444444444444" || $RecebeCNPJ == "55555555555555"
  || $RecebeCNPJ == "66666666666666" || $RecebeCNPJ == "77777777777777"
  || $RecebeCNPJ == "88888888888888" || $RecebeCNPJ == "99999999999999") {
    return false;
  } else {
    $Numero [1]  = intval(substr($RecebeCNPJ, 1 - 1, 1));
    $Numero [2]  = intval(substr($RecebeCNPJ, 2 - 1, 1));
    $Numero [3]  = intval(substr($RecebeCNPJ, 3 - 1, 1));
    $Numero [4]  = intval(substr($RecebeCNPJ, 4 - 1, 1));
    $Numero [5]  = intval(substr($RecebeCNPJ, 5 - 1, 1));
    $Numero [6]  = intval(substr($RecebeCNPJ, 6 - 1, 1));
    $Numero [7]  = intval(substr($RecebeCNPJ, 7 - 1, 1));
    $Numero [8]  = intval(substr($RecebeCNPJ, 8 - 1, 1));
    $Numero [9]  = intval(substr($RecebeCNPJ, 9 - 1, 1));
    $Numero [10] = intval(substr($RecebeCNPJ, 10 - 1, 1));
    $Numero [11] = intval(substr($RecebeCNPJ, 11 - 1, 1));
    $Numero [12] = intval(substr($RecebeCNPJ, 12 - 1, 1));
    $Numero [13] = intval(substr($RecebeCNPJ, 13 - 1, 1));
    $Numero [14] = intval(substr($RecebeCNPJ, 14 - 1, 1));
    $soma        = $Numero [1] * 5 + $Numero [2] * 4 + $Numero [3] * 3 + $Numero [4]
    * 2 + $Numero [5] * 9 + $Numero [6] * 8 + $Numero [7] * 7 + $Numero [8]
    * 6 + $Numero [9] * 5 + $Numero [10] * 4 + $Numero [11] * 3 + $Numero [12]
    * 2;
    $soma        = $soma - (11 * (intval($soma / 11)));
    if ($soma == 0 || $soma == 1) {
      $resultado1 = 0;
    } else {
      $resultado1 = 11 - $soma;
    }
    if ($resultado1 == $Numero [13]) {
      $soma = $Numero [1] * 6 + $Numero [2] * 5 + $Numero [3] * 4 + $Numero [4]
      * 3 + $Numero [5] * 2 + $Numero [6] * 9 + $Numero [7] * 8 + $Numero [8]
      * 7 + $Numero [9] * 6 + $Numero [10] * 5 + $Numero [11] * 4 + $Numero [12]
      * 3 + $Numero [13] * 2;
      $soma = $soma - (11 * (intval($soma / 11)));
      if ($soma == 0 || $soma == 1) {
        $resultado2 = 0;
      } else {
        $resultado2 = 11 - $soma;
      }
      if ($resultado2 == $Numero [14]) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}

/**
* Pega um valor moeda formatado (R$ 1.000,00) e retorna 1000.00
* @param text $str
* @return mixed
*/
function acerta_moeda($str)
{
  $str = trim($str);

  if (strlen($str) <= 0) {
    return null;
  }

  $str = str_replace(".", "", $str);
  $str = str_replace(",", ".", $str);
  $str = str_replace("R$", "", $str);
  $str = str_replace("US$", "", $str);
  $str = str_replace("U$", "", $str);
  $str = str_replace("$", "", $str);
  $str = str_replace(" ", "", $str);
  return $str;
}

/**
* Pega uma data no formato DD/MM/YYYY e retorna YYYY-MM-DD
* @param text $dt
* @return string
*/
function acerta_data($dt)
{
  if (!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $dt))
  return null;
  $temp = explode('/', $dt);
  return $temp [2].'-'.$temp [1].'-'.$temp [0];
}

/**
* Pega data hora no formato DD/MM/YYYY HH:MI:SS e retorna YYYY-MM-DD HH:MI:SS
* @param text $dt
* @return string
*/
function acerta_data_hora($dt)
{
  //if (!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})\s([0-9]{2}):([0-9]{2})$/', $dt))
  //  return null;
  $hora = substr($dt, 11, 8);
  $data = explode('/', substr($dt, 0, 10));
  $data = $data [2].'-'.$data [1].'-'.$data [0].' '.$hora;
  return $data;
}

/**
* Pega a data no formato YYYY-MM-DD e retorna DD/MM/YYYY
* @param text $dt
* @return string
*/
function formata_data($dt)
{
  $hora = substr($dt, 11, 8);
  $data = explode('-', substr($dt, 0, 10));
  $data = $data [2].'/'.$data [1].'/'.$data [0];
  return $data;
}

/**
* Pega a data no formato YYYY-MM-DD HH:MI:SS e retorna DD/MM/YYYY HH:MI:SS
* @param text $dt
* @return string
*/
function formata_data_hora($dt)
{
  $hora = substr($dt, 11, 8);
  $data = explode('-', substr($dt, 0, 10));
  $data = $data [2].'/'.$data [1].'/'.$data [0].' '.$hora;
  return $data;
}

/**
*
* @param string $email
* @return boolean
*/
function is_mail($email)
{
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    list ( $email, $domain ) = explode("@", $email);
    if (checkdnsrr($domain, "MX")) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

/**
* Valida se uma data é Valida ou nao
* @param text $date
* @param text $format
* @return boolean
*/
function isValidDate($date, $format = 'Y-m-d H:i:s')
{

  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}

function getLatDay($date = '2018-01-01')
{
  return date("Y-m-t", strtotime($date));
}

function formatMoney($value)
{
  return "R$ ".number_format($value, 2, ",", ".");
}

function formatMoney2($value)
{
  return number_format($value, 2, ",", ".");
}

function getMesByNumber($mes)
{
  $mes = (int) $mes;
  switch ($mes) {
    case 1: $mes = 'Janeiro';
    break;
    case 2: $mes = 'Fevereiro';
    break;
    case 3: $mes = 'Março';
    break;
    case 4: $mes = 'Abril';
    break;
    case 5: $mes = 'Maio';
    break;
    case 6: $mes = 'Junho';
    break;
    case 7: $mes = 'Julho';
    break;
    case 8: $mes = 'Agosto';
    break;
    case 9: $mes = 'Setembro';
    break;
    case 10: $mes = 'Outubro';
    break;
    case 11: $mes = 'Novembro';
    break;
    case 12: $mes = 'Dezembro';
    break;
  }
  return $mes;
}

function mask($val, $mask)
{

  $maskared = '';
  $val = "$val";

  $k = 0;

  for ($i = 0; $i <= strlen($mask) - 1; $i++) {

    if ($mask[$i] == '#') {

      if (isset($val[$k])) $maskared .= $val[$k++];
    }

    else {
      if (isset($mask[$i])) $maskared .= $mask[$i];
    }
  }

  return $maskared;
}

function sendPushNotifications($to=array(), $data=array()){
    $apiKey = 'AIzaSyADY0AQlCREEQTp8io1EC_iJu9BZsL4fHg'; #api key do financas
    $fields = [
        //"to"             => $to, #específico pra 1 pessoa
        "registration_ids" => $to, #1 ate 1000
        "notification"     => $data,
    ];

    $headers = [
        "Authorization: key=$apiKey",
        "Content-Type: application/json",
    ];

    $url = "https://fcm.googleapis.com/fcm/send";

    $ch     = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

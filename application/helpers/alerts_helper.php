<?php
function showAlertBlock($content, $type){
  switch($type){
    case "error":
      $title = "Erro!";
      $class = "alert-error";
      break;
    case "success":
      $title = "Sucesso!";
      $class = "alert-success";
      break;
    case "info":
      $title = "Informação!";
      $class = "alert-info";
      break;
    default:
      $title = "Alerta!";
      $class = "";
      break;
  }

  return "<div class='alert alert-block $class'>
            <a class='close' data-dismiss='alert' href='#'>×</a>
            <h4 class='alert-heading'>$title</h4>
            $content
          </div>";
}

function showWarning($content){
  return showAlertBlock($content, "warning");
}

function showError($content){
  return showAlertBlock($content, "error");
}

function showSuccess($content){
  return showAlertBlock($content, "success");
}

function showInfo($content){
  return showAlertBlock($content, "info");
}

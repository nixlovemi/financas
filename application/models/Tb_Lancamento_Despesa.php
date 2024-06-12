<?php

class Tb_Lancamento_Despesa extends CI_Model
{
    public function insert($arrDados)
    {
        $arrRet         = [];
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "";

        $retValidacao = $this->validaInsert($arrDados);
        if ($retValidacao["erro"]) {
            return $retValidacao;
        }

        $this->load->database();
        $this->load->helpers("utils");
        $this->db->trans_start();

        $vLancamentoId = isset($arrDados["ld_lan_id"]) ? $arrDados["ld_lan_id"] : null;
        $vCategoriaId = isset($arrDados["ld_bdp_id"]) ? $arrDados["ld_bdp_id"] : null;
        $vValor = isset($arrDados["ld_valor"]) ? $arrDados["ld_valor"] : null;

        $data = array(
            'ld_lan_id' => $vLancamentoId,
            'ld_bdp_id' => $vCategoriaId,
            'ld_valor' => $vValor
        );

        $this->db->insert('tb_lancamento_despesa', $data);
        $this->db->trans_complete();
        $retInsert = $this->db->trans_status();

        if (!$retInsert) {
            $arrRet["erro"] = true;
            $arrRet["msg"]  = $this->db->_error_message();
        } else {
            $arrRet["erro"] = false;
            $arrRet["msg"]  = "Lançamento Despesa inserido com sucesso!";
        }

        return $arrRet;
    }

    private function validaInsert($arrDados)
    {
        $this->load->helper('utils');

        $arrRet         = [];
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "";

        $vLancamento = isset($arrDados["ld_lan_id"]) ? $arrDados["ld_lan_id"] : "";
        if ($vLancamento != "") {
            if (!is_numeric($vLancamento)) {
                $arrRet["erro"] = true;
                $arrRet["msg"]  = "Por favor, informe o lançamento!";
                return $arrRet;
            }
        }

        $vCategoria = isset($arrDados["ld_bdp_id"]) ? $arrDados["ld_bdp_id"] : "";
        if ($vCategoria != "") {
            if (!is_numeric($vCategoria)) {
                $arrRet["erro"] = true;
                $arrRet["msg"]  = "Por favor, informe a categoria!";
                return $arrRet;
            }
        }

        $vValor = (isset($arrDados["ld_valor"])) ? (float)$arrDados["ld_valor"] : "";
        if (!is_numeric($vValor)) {
            $arrRet["erro"] = true;
            $arrRet["msg"]  = "Por favor, informe um valor válido!";
            return $arrRet;
        }

        $arrRet["erro"] = false;
        $arrRet["msg"]  = "";
        return $arrRet;
    }

    public function edit($arrDados)
    {
        $arrRet         = [];
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "";

        $retValidacao = $this->validaEdit($arrDados);
        if ($retValidacao["erro"]) {
            return $retValidacao;
        }

        $this->load->database();

        $vLdId = (isset($arrDados["ld_id"])) ? $arrDados["ld_id"] : "";
        $vLancamentoId = isset($arrDados["ld_lan_id"]) ? $arrDados["ld_lan_id"] : null;
        $vCategoriaId = isset($arrDados["ld_bdp_id"]) ? $arrDados["ld_bdp_id"] : null;
        $vValor = isset($arrDados["ld_valor"]) ? $arrDados["ld_valor"] : null;

        $data = [];
        $data["ld_id"] = $vLdId;
        $data["ld_lan_id"] = $vLancamentoId;
        $data["ld_bdp_id"] = $vCategoriaId;
        $data["ld_valor"] = $vValor;

        $this->db->where('ld_id', $vLdId);
        $retUpdate = $this->db->update('tb_lancamento_despesa', $data);
        if (!$retUpdate) {
            $arrRet["erro"] = true;
            $arrRet["msg"]  = $this->db->_error_message();
        } else {
            $arrRet["erro"] = false;
            $arrRet["msg"]  = "Lancamento Despesa editado com sucesso!";
        }

        return $arrRet;
    }

    private function validaEdit($arrDados)
    {
        $this->load->helper('utils');

        $arrRet         = [];
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "";

        $vLdId = (isset($arrDados["ld_id"])) ? $arrDados["ld_id"] : "";
        if (!$vLdId > 0) {
            $arrRet["erro"] = true;
            $arrRet["msg"]  = "ID inválido para editar o Lançamento Despesa!";
            return $arrRet;
        }

        $vLancamento = isset($arrDados["ld_lan_id"]) ? $arrDados["ld_lan_id"] : "";
        if ($vLancamento != "") {
            if (!is_numeric($vLancamento)) {
                $arrRet["erro"] = true;
                $arrRet["msg"]  = "Por favor, informe o lançamento!";
                return $arrRet;
            }
        }

        $vCategoria = isset($arrDados["ld_bdp_id"]) ? $arrDados["ld_bdp_id"] : "";
        if ($vCategoria != "") {
            if (!is_numeric($vCategoria)) {
                $arrRet["erro"] = true;
                $arrRet["msg"]  = "Por favor, informe a categoria!";
                return $arrRet;
            }
        }

        $vValor = (isset($arrDados["ld_valor"])) ? (float)$arrDados["ld_valor"] : "";
        if (!is_numeric($vValor) || (!$vValor >= 0)) {
            $arrRet["erro"] = true;
            $arrRet["msg"]  = "Por favor, informe um valor válido!";
            return $arrRet;
        }

        $arrRet["erro"] = false;
        $arrRet["msg"]  = "";
        return $arrRet;
    }

    public function delete($ldId)
    {
        $arrRet           = [];
        $arrRet["erro"]   = true;
        $arrRet["msg"]    = "";

        if (!is_numeric($ldId)) {
            $arrRet["erro"] = true;
            $arrRet["msg"]  = "ID inválido para deletar!";

            return $arrRet;
        } else {
            $this->load->database();
            $this->db->where('ld_id', $ldId);
            $retDelete = $this->db->delete('tb_lancamento_despesa');

            if (!$retDelete) {
                $arrRet["erro"] = true;
                $arrRet["msg"]  = $this->db->_error_message();
            } else {
                $arrRet["erro"] = false;
                $arrRet["msg"] = "Lançamento Despesa deletado com sucesso!";
            }

            return $arrRet;
        }
    }
}

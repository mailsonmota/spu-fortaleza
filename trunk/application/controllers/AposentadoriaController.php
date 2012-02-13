<?php

class AposentadoriaController extends BaseController
{
    public function atualizarAction()
    {
        $this->ajaxNoRender();
        
        if ($this->isPostAjax()) {
            $res = $this->_atualizarAposentadoria($this->_getParam('ids'), $this->_getParam('colunas'));
            die($res ? 'atualizado' : 'erro');
        }
    }
    
    public function enviarDadosAction()
    {
        $this->ajaxNoRender();

        if ($this->isPostAjax()) {
            $res = $this->_enviarDadosAposentadoria($this->_getParam('dados'));
            die($res ? 'true' : 'false');
        }
    }

    private function _enviarDadosAposentadoria($dados)
    {
        $dados = $this->_filtrarDadosAposentadoria($dados);

        if (!$dados)
            return false;

        try {
            $db_aap = new Application_Model_Aposentadoria();
            $aposentado = $db_aap->encontrar($dados['PRONTUARIO']);

            if ($aposentado && $aposentado->CPF == $dados['CPF_CNPJ']) {
                $dados['DTADMISSAO'] = $aposentado->DTADMISSAO;
                $dados['CARGO'] = $aposentado->CARGO;

                $db_aap_processo = new Application_Model_AposentadoriaProcesso();
                $db_aap_processo->inserir($dados);
            } else return false;
        } catch (Zend_Db_Exception $e) {
            $dados['erro'] = "Exception:({$e->getCode()}; {$e->getFile()}; {$e->getMessage()})";
            $this->_gerarLog($dados);
            return false;
        }
        return true;
    }

    private function _filtrarDadosAposentadoria($dados)
    {
        $a = array();
        $processoService = new Spu_Service_Processo($this->getTicket());
        $processo = $processoService->getProcesso($dados['id']);
        $a['PRONTUARIO'] = $dados['prontuario'];
        if (!is_numeric($a['PRONTUARIO']))
            return false;

        $id = substr($processo->assunto->nodeRef, 24);
        if (!$this->_isTipoAposentadoria($id))
            return false;

        $a['STATUS'] = 'TRAMITANDO';
        $a['NMASSUNTOPROCESSO'] = strtoupper($processo->assunto->nome);
        $a['NOMETPPROCESSO'] = strtoupper($processo->assunto->tipoProcesso->nome);

        $path = explode("/", $processo->protocolo->path);
        $a['LOTACAO_ATUAL'] = $path[0] . " - " . $path[count($path) - 1];

        $data = new DateTime(implode("/", array_reverse(explode("/", $processo->data))));
        $a['DTABERTURA'] = $data->format('d/m/y');

        $a['CPF_CNPJ'] = str_replace(array('.', '-', '/'), "", $processo->manifestante->cpf);
        $a['NOMEREQUERENTE'] = strtoupper($processo->manifestante->nome);
        $a['NUMPROCESSO'] = str_replace("_", "/", $processo->nome);
        $a['PRONTUARIO'] = (int) $a['PRONTUARIO'];

        return $a;
    }
}
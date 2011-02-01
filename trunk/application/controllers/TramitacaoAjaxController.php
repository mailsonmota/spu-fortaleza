<?php
require_once 'BaseDataTablesController.php';
class TramitacaoAjaxController extends BaseDataTablesController
{
    public function entradaAction()
    {
        $this->_rows = $this->_getCaixaEntrada();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getCaixaEntrada()
    {
        $processo = new Processo($this->getTicket());
        $processos = $processo->listarProcessosCaixaEntrada($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
        
        return $this->_convertProcessosToDataTablesRow($processos);
    }
    
    protected function _convertProcessosToDataTablesRow($processos)
    {
        $rows = array();
        foreach ($processos as $processo) {
            $row = array();
            $row['input'] = "<input type='checkbox' name='processos[]' value='" . $processo->id . "' />";
            $this->_adicionarColunasPadrao($row, $processo);
            
            $url = $this->_helper->url('detalhes', 'processo', null, array('id' => $processo->id));
            $row['detalhes'] = "<a href='$url'>Detalhes</a>";
            
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    protected function _adicionarColunasPadrao($linhaDoArray, $processo)
    {
    	$row['numero'] = $processo->numero;
        $row['data'] = $processo->data;
        $row['nomeManifestante'] = $processo->nomeManifestante;
        $row['nomeTipoProcesso'] = $processo->nomeTipoProcesso;
        $row['nomeAssunto'] = $processo->nomeAssunto;
        
        return $row;
    }
}
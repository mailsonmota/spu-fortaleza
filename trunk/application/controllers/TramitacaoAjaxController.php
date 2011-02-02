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
    	try {
	        $processo = new Processo($this->getTicket());
	        $processos = $processo->listarProcessosCaixaEntrada($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
	        return $this->_convertProcessosToDataTablesRow($processos);
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    protected function _convertProcessosToDataTablesRow($processos)
    {
        $rows = array();
        foreach ($processos as $processo) {
            $row = array();
            $row['input'] = "<input type='checkbox' name='processos[]' value='" . $processo->id . "' />";
            $row = array_merge($row, $this->_getColunasPadraoProcesso($row, $processo));
            
            $url = $this->_helper->url('detalhes', 'processo', null, array('id' => $processo->id));
            $row['detalhes'] = "<a href='$url'>Detalhes</a>";
            
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    protected function _getColunasPadraoProcesso(&$linhaDoArray, $processo)
    {
    	$row['numero'] = $processo->numero;
        $row['data'] = $processo->data;
        $row['nomeManifestante'] = $processo->nomeManifestante;
        $row['nomeTipoProcesso'] = $processo->nomeTipoProcesso;
        $row['nomeAssunto'] = $processo->nomeAssunto;
        
        return $row;
    }
    
    public function analiseAction()
    {
        $this->_rows = $this->_getCaixaAnalise();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getCaixaAnalise()
    {
    	try {
	        $processo = new Processo($this->getTicket());
	        $processos = $processo->listarProcessosCaixaAnalise($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
	        return $this->_convertProcessosToDataTablesRow($processos);
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function saidaAction()
    {
        $this->_rows = $this->_getCaixaSaida();
        
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getCaixaSaida()
    {
    	try {
	        $processo = new Processo($this->getTicket());
	        $processos = $processo->listarProcessosCaixaSaida($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
	        return $this->_convertProcessosEnviadosToDataTablesRow($processos);
    	} catch (Exception $e) {
    		return $this->_getJsonErrorRow($e);
    	}
    }
    
    protected function _convertProcessosEnviadosToDataTablesRow($processos, $checkboxColumn = true)
    {
        $rows = array();
        foreach ($processos as $processo) {
            $row = array();
            if ($checkboxColumn) {
                $row['input'] = "<input type='checkbox' name='processos[]' value='" . $processo->id . "' />";
            }
            $row = array_merge($row, $this->_getColunasPadraoProcesso($row, $processo));
            $row['destino'] = $processo->nomeProtocolo;
            
            $url = $this->_helper->url('detalhes', 'processo', null, array('id' => $processo->id));
            $row['detalhes'] = "<a href='$url'>Detalhes</a>";
            
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function enviadosAction()
    {
        $this->_rows = $this->_getCaixaEnviados();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getCaixaEnviados()
    {
    	try {
	        $processo = new Processo($this->getTicket());
	        $processos = $processo->listarProcessosCaixaEnviados($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
	        return $this->_convertProcessosEnviadosToDataTablesRow($processos, false);
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
}
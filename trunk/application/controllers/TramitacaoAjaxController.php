<?php
require_once 'BaseDataTablesController.php';
Loader::loadDao('ProcessoDao');
Loader::loadDao('CopiaProcessoDao');
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
	        $processoDao = new ProcessoDao($this->getTicket());
	        $processos = $processoDao->getCaixaEntrada($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
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
	        $processoDao = new ProcessoDao($this->getTicket());
	        $processos = $processoDao->getCaixaAnalise($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
	        return $this->_convertProcessosToDataTablesRow($processos);
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    public function copiasAction()
    {
        $this->_rows = $this->_getCopias();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getCopias()
    {
        try {
            $copiaDao = new CopiaProcessoDao($this->getTicket());
            $copias = $copiaDao->getCopias($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
            
            return $this->_convertCopiasToDataTablesRow($copias);
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
    
    protected function _convertCopiasToDataTablesRow($copias)
    {
        $rows = array();
        foreach ($copias as $copia) {
            $row = array();
            $row['input'] = "<input type='checkbox' name='copias[]' value='" . $copia->id . "' />";
            $row['numero'] = $copia->numeroProcesso;
	        $row['data'] = $copia->dataProcesso;
	        $row['nomeManifestante'] = $copia->nomeManifestanteProcesso;
	        $row['nomeTipoProcesso'] = $copia->nomeTipoProcesso;
	        $row['nomeAssunto'] = $copia->nomeAssuntoProcesso;
            
            $url = $this->_helper->url('detalhes', 'processo', null, array('id' => $copia->idProcesso));
            $row['detalhes'] = "<a href='$url'>Detalhes</a>";
            
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function arquivoAction()
    {
        $this->_rows = $this->_getCaixaArquivo();
        $this->_total = 1000;
        
        $this->_helper->layout()->disableLayout();
        $this->view->output = $this->_getOutput();
    }
    
    protected function _getCaixaArquivo()
    {
        try {
            $processoDao = new ProcessoDao($this->getTicket());
            $processos = $processoDao->getCaixaArquivo($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
            
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
	        $processoDao = new ProcessoDao($this->getTicket());
	        $processos = $processoDao->getCaixaSaida($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
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
	        $processoDao = new ProcessoDao($this->getTicket());
	        $processos = $processoDao->getCaixaEnviados($this->_getOffset(), $this->_getPageSize(), $this->_getSearch());
	        
	        return $this->_convertProcessosEnviadosToDataTablesRow($processos, false);
        } catch (Exception $e) {
            return $this->_getJsonErrorRow($e);
        }
    }
}
<?php
Loader::loadEntity('Processo');
Loader::loadDao('ProcessoDao');
Loader::loadDao('ArquivoDao');
Loader::loadEntity('Arquivo');
class ProcessoController extends BaseController
{
    public function detalhesAction()
    {
    	try {
            $idProcesso = $this->_getIdProcessoUrl();
            $processoDao = new ProcessoDao($this->getTicket());
            $processo = $processoDao->getProcesso($idProcesso);
            $processosParalelos = $processoDao->getProcessosParalelos($processo->id);
        } catch (Exception $e) {
                $this->setMessageForTheView('Não foi possível carregar o processo', 'error');
        }
        
        $this->view->processo = $processo;
        $this->view->processosParalelos = $processosParalelos;
    }
        
    public function encaminharAction()
    {
        try {
                $idProcesso = $this->_getIdProcessoUrl();
                $processoDao = new ProcessoDao($this->getTicket());
                if ($idProcesso) {
                    $processo = $processoDao->getProcesso($idProcesso);
                }
                
                $listaPrioridades = $this->_getListaPrioridades();
                $listaProtocolos = $this->_getListaProtocolos();
            
                if ($this->getRequest()->isPost()) {
                        $processo->tramitar($this->getRequest()->getPost());
                        $this->setSuccessMessage('Processo tramitado com sucesso.');
                        $this->_redirectDetalhesProcesso($idProcesso);
                }
        } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
        }
        $this->view->processo = $processo;
        $this->view->listaPrioridades = $listaPrioridades;
        $this->view->listaProtocolos = $listaProtocolos;
    }
    
    public function arquivoAction()
    {
        try {
            $arquivoHash['id'] = $this->getRequest()->getParam('id');
            $arquivoHash['nome'] = $this->getRequest()->getParam('nome');
            $arquivoDao = new ArquivoDao($this->getTicket());
            $url = $arquivoDao->getArquivoDownloadUrl($arquivoHash);
            $this->getResponse()->setRedirect($url);
        } catch (Exception $e) {
            $this->setMessageForTheView($e->getMessage(), 'error');
        }
    }
    
    protected function _getIdProcessoUrl()
    {
        $idProcesso = $this->getRequest()->getParam('id');
        return $idProcesso;
    }
    
    protected function _getListaPrioridades()
    {
        $prioridadeDao = new PrioridadeDao($this->getTicket());
        $prioridades = $prioridadeDao->fetchAll();
        $listaPrioridades = array();
        foreach ($prioridades as $prioridade) {
            $listaPrioridades[$prioridade->id] = $prioridade->descricao;
        }
        
        if (count($listaPrioridades) == 0) {
            throw new Exception(
                'Não existe nenhuma prioridade de processo cadastrada no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaPrioridades;
    }
    
    protected function _getListaProtocolos()
    {
        $protocoloDao = new ProtocoloDao($this->getTicket());
        $protocolos = $protocoloDao->getTodosProtocolos();
        $listaProtocolos = array();
        foreach ($protocolos as $protocolo) {
            $listaProtocolos[$protocolo->id] = $protocolo->descricao;
        }
        
        if (count($listaProtocolos) == 0) {
            throw new Exception(
                'Não existe nenhum protocolo cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaProtocolos;
    }
    
    protected function _redirectDetalhesProcesso($idProcesso)
    {
        $this->_helper->redirector('detalhes', $this->getController(), 'default', array('id' => $idProcesso));
    }
}
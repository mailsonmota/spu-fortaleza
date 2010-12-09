<?php
require_once('BaseTramitacaoController.php');
class AnaliseController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
        	$processosSelecionados = $this->getRequest()->getParam('processos');
        	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        	
        	$isEncaminhar = ($this->getRequest()->getParam('encaminhar', false) !== false) ? true : false;
        	$isArquivar = ($this->getRequest()->getParam('arquivar', false) !== false) ? true : false;
        	$isExterno = ($this->getRequest()->getParam('externo', false) !== false) ? true : false;
        	$isComentar = ($this->getRequest()->getParam('comentar', false) !== false) ? true : false;
        	
        	if ($isEncaminhar) {
        		$session = new Zend_Session_Namespace('encaminhar');
        		$session->processos = $processosSelecionados;
        		$this->_redirectEncaminhar();
        	} elseif ($isArquivar) {
        		$session = new Zend_Session_Namespace('arquivar');
        		$session->processos = $processosSelecionados;
        		$this->_redirectArquivar();
        	} elseif ($isExterno) {
        		$session = new Zend_Session_Namespace('encaminharExternos');
        		$session->processos = $processosSelecionados;
        		$this->_redirectEncaminharExternos();
        	} elseif ($isComentar) {
        		$session = new Zend_Session_Namespace('comentar');
        		$session->processos = $processosSelecionados;
        		$this->_redirectComentar();
        	}
        }
    	
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
    }
    
    protected function _redirectEncaminhar()
    {
    	$this->_helper->redirector('index', 'encaminhar', 'default');
    }
    
	protected function _redirectArquivar()
    {
    	$this->_helper->redirector('arquivar', 'arquivo', 'default');
    }
    
	protected function _redirectEncaminharExternos()
    {
    	$this->_helper->redirector('externo', 'encaminhar', 'default');
    }
    
	protected function _redirectComentar()
    {
    	$this->_helper->redirector('index', 'despachar', 'default');
    }
}
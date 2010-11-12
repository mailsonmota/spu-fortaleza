<?php
Loader::loadEntity('Processo');
class ProcessosController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->view->abaAtiva = $this->getAction();	
	}
	
    public function indexAction()
    {
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEntrada();
    }
    
    public function saidaAction()
    {
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaSaida();
    }
    
    public function receberAction()
    {
    	try {
    		if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('processosParaReceber')) {
    			throw new Exception("Por favor, selecione pelo menos um processo para receber.");
    		}
    		$processo = new Processo($this->getTicket());
    		$processo->receberVarios($this->getRequest()->getPost());
    	} catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEntrada();
    	}
    	$this->_redirectEmAnalise();
    }
    
	protected function _redirectEmAnalise()
    {
    	$this->_helper->redirector('analise', $this->getController(), 'default');
    }
    
	protected function _redirectEntrada()
    {
    	$this->_helper->redirector('index', $this->getController(), 'default');
    }
    
    public function analiseAction()
    {
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
    }
    
    public function enviadosAction()
    {
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEnviados();
    }
}
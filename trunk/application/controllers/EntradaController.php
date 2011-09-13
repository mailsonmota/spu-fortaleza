<?php
require_once('BaseTramitacaoController.php');
class EntradaController extends BaseTramitacaoController
{
    public function indexAction()
    {
    	$this->view->q = urldecode($this->_getParam('q'));
    	
    	$service = new Spu_Service_Tramitacao($this->getTicket());
	    $this->view->paginator = $this->_helper->paginator()->paginate(
	    	$service->getCaixaEntrada(
		    	$this->_helper->paginator()->getOffset(),
		    	$this->_helper->paginator()->getPageSize(),
		    	$this->view->q
	    	)
    	);
    }
    
    public function receberAction()
    {
        try {
            if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('processos')) {
                throw new Exception("Por favor, selecione pelo menos um processo para receber.");
            }
            
            $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
            $tramitacaoService->receberVarios($this->getRequest()->getPost());
            $this->setSuccessMessage('Processos recebidos com sucesso.');
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEntrada();
        }
        $this->_redirectEmAnalise();
    }
}
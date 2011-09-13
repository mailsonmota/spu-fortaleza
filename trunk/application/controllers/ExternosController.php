<?php
require_once('BaseTramitacaoController.php');
class ExternosController extends BaseTramitacaoController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $processosSelecionados = $this->getRequest()->getParam('processos');
                $session = new Zend_Session_Namespace('encaminhar');
                $session->processos = $processosSelecionados;
                $this->_redirectEncaminhar();
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }
        
        $this->view->q = urldecode($this->_getParam('q'));
         
        $service = new Spu_Service_Tramitacao($this->getTicket());
	    $this->view->paginator = $this->_helper->paginator()->paginate(
		    $service->getCaixaExternos(
		        $this->_helper->paginator()->getOffset(),
		        $this->_helper->paginator()->getPageSize(),
		        $this->view->q
	        )
        );
    }
}
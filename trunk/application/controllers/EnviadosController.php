<?php
require_once('BaseTramitacaoController.php');
class EnviadosController extends BaseTramitacaoController
{
    public function indexAction()
    {
    	$this->view->q = urldecode($this->_getParam('q'));
    	 
    	$service = new Spu_Service_Tramitacao($this->getTicket());
    	$this->view->paginator = $this->_helper->paginator()->paginate(
	    	$service->getCaixaSaida(
		    	$this->_helper->paginator()->getOffset(),
		    	$this->_helper->paginator()->getPageSize(),
		    	$this->view->q
	    	)
    	);
    }
}
<?php
require_once('BaseTramitacaoController.php');
class EnviadosController extends BaseTramitacaoController
{
    public function indexAction()
    {
    	$this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();
    	 
    	$service = new Spu_Service_Tramitacao($this->getTicket());
    	$this->view->paginator = $this->_helper->paginator()->paginate(
	    	$service->getCaixaEnviados(
		    	$this->_helper->paginator()->getOffset(),
		    	$this->_helper->paginator()->getPageSize(),
		    	$this->view->q, 
		    	$this->view->assuntoId
	    	)
    	);
    }
}
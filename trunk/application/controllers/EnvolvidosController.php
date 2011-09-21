<?php
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		$this->_helper->redirector(null, null, null, array('q' => $_POST['q']));
    	}
    	
    	if ($this->_getParam('q')) {
    		$service = new Spu_Service_Manifestante($this->getTicket());
    		$this->view->paginator = $this->_helper->paginator()->paginate(
	    		$service->getManifestantes(
		    		$this->_helper->paginator()->getOffset(),
		    		$this->_helper->paginator()->getPageSize(),
		    		$this->_getParam('q')
	    		)
    		);
    	} else {
    		$this->setMessageForTheView('Por favor, busque pelo Nome ou CPF.');
    	}
    	 
    	$this->view->q = $this->_getParam('q');
    }
    
    public function detalhesAction()
    {
		$manifestanteService = new Spu_Service_Manifestante($this->getTicket());
        $manifestante = $manifestanteService->getManifestante($this->_getCpfFromUrl());
        
        $this->view->manifestante = $manifestante;
        $this->view->id = $manifestante->getCpf();
        $this->view->isEdit = true;
    }
    
    private function _getCpfFromUrl()
    {
        $cpf = $this->getRequest()->getParam('cpf');
        return $cpf;
    }
}
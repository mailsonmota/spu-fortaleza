<?php
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		$this->_helper->redirector(null, null, null, array('q' => $_POST['q']));
    	}
    	 
    	$service = new Spu_Service_Manifestante($this->getTicket());
    	$this->view->paginator = $this->_helper->paginator()->paginate(
	    	$service->getManifestantes(
		    	$this->_helper->paginator()->getOffset(),
		    	$this->_helper->paginator()->getPageSize(),
		    	$this->_getParam('q')
	    	)
    	);
    	 
    	$this->view->q = $this->_getParam('q');
    }
    
    public function detalhesAction()
    {
        $cpf = $this->_getCpfFromUrl();
        
        $manifestanteService = new Spu_Service_Manifestante($this->getTicket());
        $manifestante = $manifestanteService->getManifestante($cpf);
        
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
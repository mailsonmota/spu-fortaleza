<?php
class ProtocolosController extends BaseController
{
    public function indexAction()
    {
    	if ($this->getRequest()->isPost()) {
    		$this->_helper->redirector(null, null, null, array('q' => $_POST['q']));
    	}
    	
    	$service = new Spu_Service_Protocolo($this->getTicket());
    	$this->view->paginator = $this->_helper->paginator()->paginate(
    		$service->getTodosProtocolosPaginado(
    			$this->_helper->paginator()->getOffset(), 
    			$this->_helper->paginator()->getPageSize(), 
    			$this->_getParam('q')
    		)
    	);
    	
    	$this->view->q = $this->_getParam('q');
    }
    
    public function editarAction()
    {
        $id = $this->_getIdFromUrl();

        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolo = $protocoloService->getProtocolo($id);
        
        $this->view->protocolo = $protocolo;
        $this->view->id = $protocolo->getId();
    }

    private function _getIdFromUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $id;
    }
}

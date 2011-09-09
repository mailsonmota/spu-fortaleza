<?php
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
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
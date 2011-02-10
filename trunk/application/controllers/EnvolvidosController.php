<?php
Loader::loadService('ManifestanteService');
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
        $manifestanteService = new ManifestanteService($this->getTicket());
        $this->view->lista = $manifestanteService->getManifestantes();
    }
    
    public function editarAction()
    {
        $cpf = $this->_getCpfFromUrl();
        
        $manifestanteService = new ManifestanteService($this->getTicket());
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
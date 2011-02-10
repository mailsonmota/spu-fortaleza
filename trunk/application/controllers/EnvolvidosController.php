<?php
Loader::loadDao('ManifestanteDao');
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
        $manifestanteDao = new ManifestanteDao($this->getTicket());
        $this->view->lista = $manifestanteDao->getManifestantes();
    }
    
    public function editarAction()
    {
        $cpf = $this->_getCpfFromUrl();
        
        $manifestanteDao = new ManifestanteDao($this->getTicket());
        $manifestante = $manifestanteDao->getManifestante($cpf);
        
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
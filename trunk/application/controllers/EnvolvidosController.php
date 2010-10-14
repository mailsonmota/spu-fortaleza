<?php
Loader::loadEntity('Manifestante');
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
        $manifestante = new Manifestante($this->getTicket());
        $this->view->lista = $manifestante->listar();
    }
    
    public function editarAction()
    {
        $cpf = $this->_getCpfFromUrl();
        
        $manifestante = new Manifestante($this->getTicket());
        $manifestante->carregarPeloCpf($cpf);
        
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
<?php
Loader::loadEntity('TipoProcesso');
class TiposprocessoController extends BaseController
{
    public function indexAction()
    {
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $this->view->lista = $tipoProcesso->listar();
    }
    
    public function editarAction()
    {
        $id = $this->getIdFromUrl();
        
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $tipoProcesso->carregarPeloId($id);
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }
    
    public function assuntosAction()
    {
        $id = $this->getIdFromUrl();
        
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $tipoProcesso->carregarPeloId($id);
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }
    
    private function getIdFromUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $id;
    }
}
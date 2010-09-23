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
        $tipoProcesso = new TipoProcesso();
    }
}
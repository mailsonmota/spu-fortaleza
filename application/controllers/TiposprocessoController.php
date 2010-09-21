<?php
Loader::loadEntity('TipoProcesso');
class TiposprocessoController extends BaseController
{
    public function indexAction()
    {
        $user = AuthPlugin::getIdentity();
        $ticket = $user['ticket'];
        
        $tipoProcesso = new TipoProcesso($ticket);
        $this->view->lista = $tipoProcesso->listar();
    }
}
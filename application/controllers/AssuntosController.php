<?php
Loader::loadEntity('Assunto');
class AssuntosController extends BaseController
{
    public function indexAction()
    {
        $user = AuthPlugin::getIdentity();
        $ticket = $user['ticket'];
        
        $assunto = new Assunto($ticket);
        $this->view->lista = $assunto->listar();
    }
}
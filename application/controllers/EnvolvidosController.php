<?php
Loader::loadEntity('Manifestante');
class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
        $manifestante = new Manifestante($this->getTicket());
        $this->view->lista = $manifestante->listar();
    }
}
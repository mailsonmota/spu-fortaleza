<?php
Loader::loadEntity('Processo');
class ProcessosController extends BaseController
{
    public function indexAction()
    {
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEntrada();
    }
}
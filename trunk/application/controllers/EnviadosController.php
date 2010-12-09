<?php
require_once('BaseTramitacaoController.php');
class EnviadosController extends BaseTramitacaoController
{
	public function indexAction()
    {
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEnviados();
    }
}
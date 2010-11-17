<?php
class IndexController extends BaseController
{
    public function indexAction() {
    	$processo = new Processo($this->getTicket());
    	$this->view->listaEntrada = $processo->listarProcessosCaixaEntrada();
    	$this->view->listaAnalise = $processo->listarProcessosCaixaAnalise();
    }
}


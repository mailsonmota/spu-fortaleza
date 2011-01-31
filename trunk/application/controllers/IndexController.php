<?php
class IndexController extends BaseController
{
    public function indexAction()
    {
    	if ($this->view->pessoa->isGuest()) {
    		$this->setMessageForTheView('Você não tem acesso à nenhum setor de protocolo, portanto
    		                              não poderá abrir ou tramitar processos.');
    	}
    	
    	echo $this->getTicket();
    }
}


<?php
class IndexController extends BaseController
{
    public function indexAction()
    {
        if ($this->view->pessoa->isGuest()) {
            $this->setMessageForTheView('Você não tem acesso à nenhum setor de protocolo, portanto
                                  não poderá abrir ou tramitar processos.');
        }
        
        //FIXME: Debug Select de Protocolos
        $service = new Spu_Service_Protocolo($this->getTicket());
        $this->view->protocolos = $service->getProtocolosRaiz();
    }
}

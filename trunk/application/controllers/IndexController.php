<?php
class IndexController extends BaseController
{
    public function indexAction()
    {
        if ($this->view->pessoa->isGuest()) {
            $this->setMessageForTheView('Você não tem acesso à nenhum setor de protocolo, portanto
                                  não poderá abrir ou tramitar processos.');
        }
        
        $srv = new Alfresco_Rest_SpacesStore(Spu_Service_Abstract::getAlfrescoUrl(), $this->getTicket());
        $result = $srv->getRootFolders();
        
        echo '<pre>'; var_dump($result); exit;
    }
}

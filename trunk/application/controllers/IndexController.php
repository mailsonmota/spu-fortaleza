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
    
        $rootFolders = $srv->getRootFolders();
        
        $childrenNodes = $srv->getChildren($rootFolders[4]->id);
        
        $protocolos = $srv->getChildren($childrenNodes[0]->id);
        
        echo '<pre>'; var_dump($protocolos);
    }
}

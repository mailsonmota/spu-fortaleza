<?php
Loader::loadAlfrescoObject('Alfresco');
require_once('../library/Alfresco/Service/Session.php');
require_once('../library/Alfresco/Service/SpacesStore.php');
class Space extends Alfresco
{
    protected $_details;
    
    public function loadSpace($spaceAddress)
    {
        try {
            $session = $this->getSession();
            $store = $session->getStore($spaceAddress);
            
            $spacesStore = new AlfSpacesStore($session);
            
            $this->setDetails($store);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function getDetails()
    {
        return $this->_details;
    }
    
    private function setDetails($details)
    {
        $this->_details = $details;
    }
}
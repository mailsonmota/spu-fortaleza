<?php
Loader::loadAlfrescoObject('Alfresco');
require_once('../library/Alfresco/Service/AccessControl.php');
class Group extends Alfresco
{
    public function getGroups()
    {
        $accessControl = new AlfAccessControl(self::REPOSITORY_URL, $this->getTicket());
        $groups = $accessControl->getAuthorities();
        echo '<pre>';
        var_dump($groups);
        echo '</pre>';
    }
}
?>
<?php
Loader::loadAlfrescoObject('Alfresco');
require_once('../library/Alfresco/Service/Administration.php');
class User extends Alfresco
{
    protected $_details;
    
    public function loadUser($username)
    {
        $administration = new AlfAdministration(self::REPOSITORY_URL, $this->getTicket());
        $this->setDetails($administration->getUser($username));
    }
    
    public function getDetails()
    {
        return $this->_details;
    }
    
    private function setDetails($details)
    {
        $this->_details = $details;
    }
    
    public function getFirstName()
    {
        return $this->getDetail('first_name');
    }
    
    public function getLastName()
    {
        return $this->getDetail('last_name');
    }
    
    public function getEmail()
    {
        return $this->getDetail('email');
    }
    
    public function getHomeFolder()
    {
        return $this->getDetail('home_folder');
    }
    
    protected function getDetail($detailName)
    {
        $details = $this->getDetails();
        return $details->$detailName;
    }
    
    public function getDisplayName()
    {
        return $this->getFullName();
    }
    
    public function getFullName()
    {
        $fullName = $this->getFirstName() . ' ' . $this->getLastName();
        return $fullName;
    }
}
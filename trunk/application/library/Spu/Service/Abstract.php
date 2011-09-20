<?php
class Spu_Service_Abstract extends Alfresco_Rest_Abstract
{
    public function __construct($ticket = null)
    {
    	$this->setBaseUrl(self::getAlfrescoUrl());
    	if (isset($ticket)) {
            $this->setTicket($ticket);
        }
    }
    
    public static function getAlfrescoUrl()
    {
    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
    	return $config->get('alfresco')->get('url') . '/service';
    }
    
    protected function _getHashValue($hash, $hashField)
    {
        if (!isset($hash[$hashField])) {
            return null;
        }
        
        if (is_array($hash[$hashField])) {
            $value = array();
            foreach ($hash[$hashField] as $hashValue) {
                $value[] = $hashValue;
            }
        } else {
            $value = $hash[$hashField];
        }
        return $value;
    }
}
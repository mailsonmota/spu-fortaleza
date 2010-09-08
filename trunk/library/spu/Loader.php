<?php
class Loader
{
    const MAIN_SERVICES_FOLDER = '../library/Alfresco';
    const MAIN_FOLDER = '../library/spu';
    
    public static function loadEntity($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/entities/');
    }
    
    public static function loadEnumeration($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/enumerations/');
    }
    
    public static function loadAlfrescoObject($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/alfresco/');
    }
    
    public static function loadAlfrescoService($className)
    {
        require_once(self::MAIN_SERVICES_FOLDER . '/' . $className . '.php');
    }
    
    public static function loadModel($classFileName)
    {
        require_once(APPLICATION_PATH . '/models/' . $classFileName);
    }
}
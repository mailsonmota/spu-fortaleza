<?php
class Loader
{
    const MAIN_SERVICES_FOLDER = '../application/library/Alfresco';
    const MAIN_FOLDER = '../application/library/Spu';
    
    public static function loadBaseAlfrescoClass()
    {
    	Zend_Loader::loadClass('BaseAlfrescoClass', self::MAIN_FOLDER);
    }
    
	public static function loadDao($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/daos/');
    }
    
    public static function loadEntity($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/entities/');
    }
    
    public static function loadClassification($classFileName)
    {
    	Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/classifications/');
    }
    
	public static function loadAspect($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/aspects/');
    }
    
    public static function loadEnumeration($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_FOLDER . '/enumerations/');
    }
    
    public static function loadAlfrescoApiClass($classFileName)
    {
        Zend_Loader::loadClass($classFileName, self::MAIN_SERVICES_FOLDER . '/API/');
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
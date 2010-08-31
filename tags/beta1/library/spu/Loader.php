<?php
class Loader
{
    const MAIN_FOLDER = '../library/spu/';
    
    public static function loadEntity($classFileName)
    {
        require_once(self::MAIN_FOLDER . 'entities/' . $classFileName);
    }
    
    public static function loadEnumeration($classFileName)
    {
        require_once(self::MAIN_FOLDER . 'enumerations/' . $classFileName);
    }
    
    public static function loadModel($classFileName)
    {
        require_once(APPLICATION_PATH . '/models/' . $classFileName);
    }
}
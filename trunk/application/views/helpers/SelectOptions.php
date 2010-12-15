<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Selectoptions extends Zend_View_Helper_Abstract
{
    public function selectoptions(array $arrayOfObjects, $valueField, $textField)
    {
        $options = array();
        foreach ($arrayOfObjects as $object) {
            $options[$object->$valueField] = $object->$textField;
        }
        
        return $options;
    }
}

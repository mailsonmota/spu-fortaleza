<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_select extends Zend_View_Helper_form
{
    public function objectToOptions(array $arrayOfObjects, $valueField, $textField)
    {
        $options = array();
        foreach ($arrayOfObjects as $object) {
            $options[$object->$valueField] = $object->$textField;
        }
        
        return $options;
    }
    
    public function select($label, $name, $selectOptions = array(), $selectedValue = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        
        $id = $this->getId();
        
        $multiple = (isset($this->_options['multiple'])) ? 'MULTIPLE' : '';
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>";
        $html .= "<select $multiple name=\"$name\" id=\"$id\">";
        foreach ($selectOptions as $key => $value) {
            $selected = ($key == $selectedValue) ? 'selected="selected"' : '';
            $html .= "<option value=\"$key\" $selected>$value</option>";
        }
        $html .= "</select>";
        $html .= "</dd>";
        
        return $html;
    }
}
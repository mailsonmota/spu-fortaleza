<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Checklist extends Zend_View_Helper_form
{
    public function checklist($label, $name, array $checklistOptions, array $checkedValues = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        $id = $this->getId();
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>";
        $html .= "<ul>";
        $i = 0;
        foreach ($checklistOptions as $key => $value) {
            $liClass = ((++$i % 2) == 0) ? 'class="odd"' : 'class="even"';
            $checked = (in_array($key, $checkedValues)) ? 'checked="checked"' : null;
            $html .= "<li $liClass><input type=\"checkbox\" name=\"$name\" id=\"$id\" value=\"$key\" $checked/>$value</li>";
        }
        $html .= "</ul>";
        $html .= "</dd>";
        
        return $html;
    }
}
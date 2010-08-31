<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_checkbox extends Zend_View_Helper_form
{
    public function checkbox($label, $name, $checked = false, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        
        $id = $this->getId();
        
        $checked = ($checked) ? 'checked="checked"' : '';
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd><input type=\"checkbox\" name=\"$name\" id=\"$id\" $checked/></dd>";
        
        return $html;
    }
}
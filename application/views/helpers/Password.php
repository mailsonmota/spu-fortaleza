<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_password extends Zend_View_Helper_textbox
{
    public function password($label, $name, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        $inputClass = $this->getInputClass();
        $length = $this->getHtmlLength();
        $id = $this->getId();
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd><input type=\"password\" autocomplete=\"off\" name=\"$name\" id=\"$id\" $length $inputClass/></dd>";
        
        return $html;
    }
}
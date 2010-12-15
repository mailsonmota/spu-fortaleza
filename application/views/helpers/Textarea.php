<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Textarea extends Zend_View_Helper_Form
{
    public function textarea($label, $name, $value = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        $inputClass = $this->getInputClass();
        $length = $this->getHtmlLength();
        $id = $this->getId();
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd><textarea name=\"$name\" id=\"$id\" $length $inputClass>$value</textarea></dd>";
        
        return $html;
    }
    
	public function getInputClass()
    {
        $class  = '';
        $labelClass = $this->getLabelClass();        

        if ($labelClass) {
            $class .= "class=\"$labelClass\"";
        } 
        
        return $class;
    }
    
    public function getHtmlLength()
    {
        $length = $this->getLength();
        $html = ($length) ? "maxlength=\"$length\"" : '';
        
        return $html;
    }
    
    public function getLength()
    {
        return (isset($this->_options['length'])) ? $this->_options['length'] : 0;
    }
}

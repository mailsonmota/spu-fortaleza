<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_textbox extends Zend_View_Helper_form
{
    public function textbox($label, $name, $value = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        $inputClass = $this->getInputClass();
        $length = $this->getHtmlLength();
        $id = $this->getId();
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd><input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" $length $inputClass/></dd>";
        
        return $html;
    }
    
    public function getInputClass()
    {
        $class  = '';
        $lengthClass = $this->getClassByLength($this->getLength());
        
       if ($lengthClass) {
           $class .= "class=\"$lengthClass\"";
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
    
    public function getClassByLength($length)
    {
        $class = '';
        if ($length <= 20) {
            $class = '';
        } elseif ($length <= 60) {
            $class = "middleText";
        } else {
            $class = "largeText";
        }
        
        return $class;
    }
}
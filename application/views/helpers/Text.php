<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Text extends Zend_View_Helper_Form
{
    public function text($label, $value = null, array $options = array())
    {
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        
        if ($this->_isStrong() AND $value) {
        	$value = "<strong>$value</strong>";
        }
        
        $html  = "";
        $html .= "<dt><label class=\"$labelClass\">$label:</label></dt>";
        if ($value) $html .= "<dd class=\"campoTexto\">$value</dd>";
        
        return $html;
    }
    
    protected function _isStrong()
    {
    	return (isset($this->_options['strong'])) ? $this->_options['strong'] : false;
    }
}
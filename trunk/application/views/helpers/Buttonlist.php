<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_buttonlist extends Zend_View_Helper_Abstract
{
    protected $_options = array();
    protected $_buttonOptions = array();
    
    public function buttonlist(array $options = array())
    {
        $this->_options = $options;
        
        return $this;
    }
    
    public function open()
    {
        $class = (isset($this->_options['class'])) ? $this->_options['class'] : '';
        $html = "<ul class=\"buttons $class\">";
        
        return $html;
    }
    
    public function close()
    {
        $html = '</ul>';
        
        return $html;
    }
    
    public function description($text)
    {
        $html = "<li class=\"description\">$text</li>";
        
        return $html;
    }
    
    public function button($text, $name = null, array $buttonOptions = array())
    {
        $this->_buttonOptions = $buttonOptions;

        $type = $this->getButtonType();
        
        $htmlName = ($name) ? "name=\"$name\"" : '';
        $htmlConfirmation = $this->getConfirmation();
        
        $html = "<li><button type=\"$type\" $htmlName $htmlConfirmation>$text</button></li>";
        
        return $html;
    }
    
    protected function getButtonType()
    {
        $type = (isset($this->_buttonOptions['type'])) ? $this->_buttonOptions['type'] : 'submit';
        
        return $type;
    }
    
    protected function getConfirmation()
    {
        if (isset($this->_buttonOptions['confirmation'])) {
            if ($this->_buttonOptions['confirmation'] === true) {
                $textoConfirmacao = 'Tem certeza que deseja realizar esta ação?';
            } else {
                $textoConfirmacao = $this->_buttonOptions['confirmation'];
            }
        }
        
        $html = "onClick=\"return confirm('$textoConfirmacao');\"";
        
        return $html;
    }
}
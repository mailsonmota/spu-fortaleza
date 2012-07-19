<?php

/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Buttonlist extends Zend_View_Helper_Abstract
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
        return '</ul>';
    }

    public function description($text)
    {
        return "<li class=\"description\">$text</li>";
    }

    public function defaultBlockActionsDescription()
    {
        return $this->description('Ações em bloco:');
    }

    public function button($text, $name = null, array $buttonOptions = array(), $atributos = array())
    {
        $this->_buttonOptions = $buttonOptions;

        $type = $this->getButtonType();
        $atributos = $this->transfomeAtributos($atributos);
        
        $htmlName = ($name) ? "name=\"$name\"" : '';
        $htmlConfirmation = $this->getConfirmation();

        $onClick = (isset($buttonOptions['onClick'])) ? 'onClick="' . $buttonOptions['onClick'] . '"' : null;

        $html = "<li><button type=\"$type\" $htmlName $htmlConfirmation $onClick $atributos>$text</button></li>";

        return $html;
    }

    protected function getButtonType()
    {
        return (isset($this->_buttonOptions['type'])) ? $this->_buttonOptions['type'] : 'submit';
    }

    protected function getConfirmation()
    {
        $html = '';
        if (isset($this->_buttonOptions['confirmation'])) {
            $textoConfirmacao = '';
            if ($this->_buttonOptions['confirmation'] === true) {
                $textoConfirmacao = 'Tem certeza que deseja realizar esta ação?';
            } else {
                $textoConfirmacao = $this->_buttonOptions['confirmation'];
            }
            $html = "onclick=\"return confirm('$textoConfirmacao');\"";
        }

        return $html;
    }

    public function resetbutton()
    {
        return "<li><button type=\"reset\">Limpar</button></li>";
    }

    protected function transfomeAtributos($atributos)
    {
        $html = "";
        
        foreach ($atributos as $key => $value)
            $html .= "$key='$value' ";
        
        return $html;
    }

}
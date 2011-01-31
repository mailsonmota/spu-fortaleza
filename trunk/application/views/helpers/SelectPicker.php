<?php
/**
 * Cria uma seleção múltipla com opço de escolha
 * 
 * @author Bruno Cavalcante
 * @since 15/12/2010
 */
require_once('Selectmultiple.php');
class Zend_View_Helper_SelectPicker extends Zend_View_Helper_Selectmultiple
{
    const CHOOSELINK_TEXT = 'Escolher';

    public function selectPicker($label, $name, array $selectOptions = array(), array $selectedValues = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        $id = $this->getId();
        
        $chooseLink = $this->_getChooseLink();

        $html  = "";
        $html .= "<dt><label for=\"" . $this->_getCleanedUpName() . "\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>";
        $html .= "<select name=\"$name\" id=\"" . $this->_getCleanedUpName() . "\" multiple=\"true\">";
        $i = 0;
        foreach ($selectOptions as $key => $value) {
            $selected = (is_array($selectedValues) AND in_array($key, $selectedValues)) ? 'selected="selected"' : null;
            $html .= "<option value=\"$key\">$value</option>";
        }
        $html .= "</select> $chooseLink";
        $html .= "</dd>";
        
        return $html;
    }

    protected function _getChooseLink()
    {
        $chooseLink = '';
        if ($this->_hasChooseLink()) {
            $chooseLink = '<a rel="modal" href="#modal_' . $this->_getCleanedUpName() . '" class=\"jqModal\">' . self::CHOOSELINK_TEXT . '</a>';
        }

        return $chooseLink;
    }

    protected function _getCleanedUpName()
    {
    	return preg_replace("'[[]]'", '', $this->_name);
    }
    
    protected function _hasChooseLink()
    {
        return (isset($this->_options['chooseLink'])) ? true : false;
    }
}

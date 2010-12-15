<?php
/**
 * Cria uma seleção múltipla
 * 
 * @author Bruno Cavalcante
 * @since 13/12/2010
 */
class Zend_View_Helper_selectmultiple extends Zend_View_Helper_form
{
    public function selectmultiple($label, $name, array $selectOptions, array $selectedValues = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        $id = $this->getId();
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>";
        $html .= "<select name=\"$name\" id=\"$name\" multiple=\"true\">";
        $i = 0;
        foreach ($selectOptions as $key => $value) {
            $selected = (is_array($selectedValues) AND in_array($key, $selectedValues)) ? 'selected="selected"' : null;
            $html .= "<option value=\"$key\">$value</option>";
        }
        $html .= "</select> $chooseLink";
        $html .= "</dd>";
        
        return $html;
    }
}

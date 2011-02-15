<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_Select extends Zend_View_Helper_Form
{
	const CHOOSELINK_TEXT = 'Escolher';
	
    public function objectToOptions(array $arrayOfObjects, $valueField, $textField)
    {
        $options = array();
        foreach ($arrayOfObjects as $object) {
            $options[$object->$valueField] = $object->$textField;
        }
        
        return $options;
    }
    
    public function select($label, $name, $selectOptions = array(), $selectedValue = null, array $options = array())
    {
        $this->_name = $name;
        $this->_options = $options;
        
        $labelClass = $this->getLabelClass();
        
        $id = $this->getId();
        
        $multiple = (isset($this->_options['multiple'])) ? 'MULTIPLE' : '';
        
        $chooseLink = $this->_getChooseLink();
        
        $html  = "";
        $html .= "<dt><label for=\"$name\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>";
        $html .= "<select $multiple name=\"$name\" id=\"$id\">";
        if (is_array($selectOptions)) {
            foreach ($selectOptions as $key => $value) {
                $selected = ($key == $selectedValue) ? 'selected="selected"' : '';
                $html .= "<option value=\"$key\" $selected>$value</option>";
            }
        }
        $html .= "</select> $chooseLink";
        $html .= "</dd>";
        
        return $html;
    }
    
	protected function _getChooseLink()
    {
        $chooseLink = '';
        if ($this->_hasChooseLink()) {
            $chooseLink = '<a rel="modal" href="#modal_' . $this->_name . '">' . self::CHOOSELINK_TEXT . '</a>';
        }

        return $chooseLink;
    }

    protected function _hasChooseLink()
    {
        return (isset($this->_options['chooseLink'])) ? true : false;
    }
    
    protected function getId()
    {
        $id = $this->_name;
        if (isset($this->_options['id'])) {
            $id = $this->_options['id'];
        }
        
        return $id;
    }
    
    public function getLabelClass()
    {
        $class = '';
        if (isset($this->_options['required'])) {
            $class .= 'required';
        }
        
        return $class;
    }
}

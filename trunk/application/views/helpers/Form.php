<?php
/**
 * Retorna uma URL do tipo 
 * controller/action/param1/param1value/param2/param2value... resetando os 
 * parâmetros já existentes
 * 
 * @author Bruno Cavalcante
 * @since 08/06/2010
 */
class Zend_View_Helper_form extends Zend_View_Helper_Abstract
{
    protected $_options = array();
    protected $_name;
    
    public function form()
    {
        return $this;
    }
    
    public function openFieldList()
    {
        $html  = '';
        $html .= '<dl class="form">';
        
        return $html;
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
    
    public function closeFieldList()
    {
        $html  = '';
        $html .= '</dl>';
        
        return $html;
    }
}
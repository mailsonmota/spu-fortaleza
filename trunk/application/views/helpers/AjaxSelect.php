<?php
class Zend_View_Helper_AjaxSelect extends Zend_View_Helper_Abstract
{
    protected $_label;
    protected $_name;
    protected $_ajaxUrl;
    protected $_options;
    protected $_html;
    
    const MAX_RESULT_SIZE = 20;
    
    /**
     * ajaxSelectMultiple
     * 
     * @param $ajaxUrl
     * @param $options [id, required]
     */
    public function ajaxSelect($label, $name, $ajaxUrl, $options = array())
    {
        $this->_label = $label;
        $this->_name = $name;
        $this->_ajaxUrl = $ajaxUrl;
        $this->_options = $options;
        
        $this->_prepareHtml();
        $this->_prepareScript();
        
        return $this->_html;
    }
    
    protected function _prepareHtml()
    {
        $label = $this->_label;
        $labelClass = $this->_getLabelClass();
        $name = $this->_name;
        $id = $this->_getId();
        $autoCompleteId = $this->_getAutoCompleteId();
        $listId = $this->_getListId();
        
        $html  = "";
        $html .= "<dt><label for=\"$id\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>
                      <ul id=\"$listId\"></ul>
                      <input type=\"text\" id=\"$autoCompleteId\" class=\"autocomplete\"/>
                      <input type=\"hidden\" name=\"$name\" id=\"$id\" class=\"$labelClass\" />";
        $html .= $this->_getHtmlAfterInput();
        $html .= "</dd>";
        
        $this->_html = $html;
    }
    
    protected function _getId()
    {
        $id = $this->_name;
        if (isset($this->_options['id'])) {
            $id = $this->_options['id'];
        }
        
        return $id;
    }
    
    protected function _getLabelClass()
    {
        $class = '';
        if (isset($this->_options['required'])) {
            $class .= 'required';
        }
        
        return $class;
    }

    protected function _getAutoCompleteId()
    {
        $id = $this->_getId();
        return $id . '_autocomplete';
    }
    
    protected function _getListId()
    {
        $id = $this->_getId();
        return $id . '_list';
    }
    
    protected function _getHtmlAfterInput()
    {
        return '';
    }
    
    protected function _prepareScript()
    {
        $id = $this->_getId();
        $autoCompleteId = $this->_getAutoCompleteId();
        $listId = $this->_getListId();
        $ajaxUrl = $this->_ajaxUrl;
        
        $js = "jQuery(document).ready(function() {
                   $('#$id').hide();

                   $('#$autoCompleteId').autocomplete({
                       source: '$ajaxUrl',
                       minLength: 2,
                       autoFocus: true, 
                       select: function(event, ui) {
                           addListAndInputItem('$id', '$listId', ui.item.id, ui.item.label);
                           $('#$autoCompleteId').val('');

                           return false;
                       }
                   });
                   " . $this->_getAdditionalScript() . "
               });";
        
        $this->view->headScript()->appendScript($js, 'text/javascript');
    }
    
    protected function _getAdditionalScript()
    {
        return '';
    }
    
    protected function _getOption($optionName)
    {
        return (isset($this->_options[$optionName])) ? $this->_options[$optionName] : null;
    }
}
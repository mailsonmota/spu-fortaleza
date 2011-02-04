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
        
        $html  = "";
        $html .= "<dt><label for=\"$id\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>
                      <input type=\"text\" id=\"$autoCompleteId\" />
                      <input type=\"hidden\" name=\"$name\" id=\"$id\" />
                  </dd>";
        
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
    
    protected function _prepareScript()
    {
        $id = $this->_getId();
        $autoCompleteId = $this->_getAutoCompleteId();
        $ajaxUrl = $this->_ajaxUrl;
        
        $js = "jQuery(document).ready(function() {
                   $('#$id').hide();

                   $('#$autoCompleteId').autocomplete({
                       source: '$ajaxUrl',
                       minLength: 3,
                       select: function(event, ui) {
                           $('#$id').val(ui.item.id);
                       }
                   });
               });";
        
        $this->view->headScript()->appendScript($js, 'text/javascript');
    }
}
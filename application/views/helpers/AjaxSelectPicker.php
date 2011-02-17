<?php
require_once 'AjaxSelect.php';
class Zend_View_Helper_AjaxSelectPicker extends Zend_View_Helper_AjaxSelect
{
    protected $_ajaxDataTableUrl;
    protected $_ajaxDataTableColumns;
    
    public function ajaxSelectPicker($label, $name, $ajaxUrl, $ajaxDataTableUrl, $ajaxDataTableColumns, $options)
    {
        $this->_ajaxDataTableUrl = $ajaxDataTableUrl;
        $this->_ajaxDataTableColumns = $ajaxDataTableColumns;
        
        return parent::ajaxSelect($label, $name, $ajaxUrl, $options);
    }
    
    protected function _getHtmlAfterInput()
    {
        $name = $this->_name;
        $modalId = "modal-$name";
        $linkName = $this->_getLinkName();
        
        $html  = " <a href=\"#$modalId\" rel=\"modal\">$linkName</a>";
        
        $dataTable = $this->view->ajaxDataTable($this->_ajaxDataTableUrl, $this->_ajaxDataTableColumns)->render();
        $html .= "<div id=\"modal-wrap\">
                      <div class=\"overlay\">
                          <div id=\"$modalId\" class=\"modal\" style=\"display: none\">$dataTable</div>
                      </div>
                  </div>";
        
        return $html;
    }
    
    protected function _getLinkName()
    {
        return ($this->_getOption('linkName')) ? $this->_getOption('linkName') : 'Escolher';
    }
}
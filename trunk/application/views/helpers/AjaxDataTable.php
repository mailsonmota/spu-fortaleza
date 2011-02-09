<?php
require_once('Proxy.php');
class Zend_View_Helper_AjaxDataTable extends Zend_View_Helper_Proxy
{
	const TABLE_CLASS = 'grid no-datatable';
    
    protected $_ajaxUrl;
    protected $_columns;
    protected $_options;
    protected $_pageSize = 20;
    protected $_html;
    
	public function ajaxDataTable($ajaxUrl = '', array $columns = array(), array $options = array())
    {
        $this->_html = '';
        $this->_ajaxUrl = $ajaxUrl;
        $this->_columns = $columns;
        $this->_options = $options;
        
        $this->_prepare();
        
        return $this;
    }
    
    protected function _prepare()
    {
        $this->_prepareHeader();
        $this->_prepareFooter();
        $this->_prepareBody();
    }
    
    protected function _prepareJavascript()
    {
        $script = 'jQuery(document).ready(function() {
                        $("#' . $this->_getId() . '").dataTable({
                            "oLanguage": {
                                "sProcessing":   "<span>Processando...</span>",
                                "sLengthMenu":   "Mostrar _MENU_ registros",
                                "sZeroRecords":  "Não foram encontrados resultados",
                                "sInfo":         "Exibindo registros de _START_ a _END_",
                                "sInfoEmpty":    "Exibindo de 0 a 0 de 0 registros",
                                "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
                                "sInfoPostFix":  "",
                                "sSearch":       "Busca Rápida:",
                                "sUrl":          "",
                                "oPaginate": {
                                    "sFirst":    "«« Primeiro",
                                    "sPrevious": "« Anterior",
                                    "sNext":     "Seguinte »",
                                    "sLast":     "Último »»"
                                }
                            },
                            "bAutoWidth": false, 
                            iDisplayLength: ' . $this->_pageSize . ', 
                            sPaginationType: "text_only", 
                            "bLengthChange": false, 
                            "bSort": false, 
                            "bProcessing": true,
                            "bServerSide": true,
                            "sAjaxSource": "' . $this->_ajaxUrl . '", 
                            "fnServerData": fnDataTablesPipeline
                        }).fnSetFilteringDelay();
                    });';
        
        $this->view->headScript()->appendScript($script, 'text/javascript');
    }
    
    protected function _getId()
    {
        return (isset($this->_options['id'])) ? $this->_options['id'] : 'grid-ajax';
    }
    
    protected function _prepareHeader()
    {
        $tableClass = self::TABLE_CLASS;
        $tableId = $this->_getId();
        $html  = "<table class=\"$tableClass\" id=\"$tableId\" >";
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= $this->_getBeforeHeaderColumns();
        foreach ($this->_columns as $column) {
            $html .= '<th>' . $column . '</th>';
        }
        $html .= $this->_getAfterHeaderColumns();
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tfoot><tr></tr></tfoot>';
        
        $this->_html .= $html;
    }
    
    protected function _getBeforeHeaderColumns()
    {
        return '';
    }
    
    protected function _getAfterHeaderColumns()
    {
        return '';
    }
    
    protected function _prepareFooter()
    {
        $html  = '';
        $html .= '<tfoot></tfoot>';
        
        $this->_html .= $html;
    }
    
    protected function _getNumberOfColumns()
    {
        return 1;
    }
    
    protected function _prepareBody()
    {
        $html  = '';
        $html .= '<tbody></tbody>';
        $html .= '</table>';
        
        $this->_html .= $html;
    }
    
    public function render()
    {
    	$this->_prepareJavascript();
    	
        return $this->_html;
    }
}

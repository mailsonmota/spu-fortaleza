<?php
abstract class Zend_View_Helper_AjaxDataTable_Abstract extends Zend_View_Helper_Abstract
{
    const TABLE_CLASS = 'grid no-datatable';
    const TABLE_ROW_EVEN_CLASS = 'even';
    const TABLE_ROW_ODD_CLASS = 'odd';
    const INDENT_MARKUP = '&nbsp;&nbsp;&nbsp;&nbsp;';
    const LEVEL_SEPARATOR = ' / ';
    
    protected $_ajaxUrl;
    protected $_columns;
    protected $_options;
    protected $_pageSize = 10;
    protected $_html;
    
    public function __construct($ajaxUrl = '', array $columns = array(), array $options = array())
    {
        $this->_html = '';
        $this->_ajaxUrl = $ajaxUrl;
        $this->_columns = $columns;
        $this->_options = $options;
        
        $this->_prepare();
        
        return $this;
    }
    
    public function render()
    {
        return $this->_html;
    }
    
    protected function _prepare()
    {
    	$this->_prepareJavascript();
        $this->_prepareHeader();
        $this->_prepareFooter();
        $this->_prepareBody();
    }
    
    protected function _prepareJavascript()
    {
    	$html  = '';
    	$html .= '<script type="text/javascript">
			    	jQuery(document).ready(function() {
			            $("#' . $this->_getId() . '").dataTable({
			                "oLanguage": {
			                    "sProcessing":   "<span>Processando...</span>",
			                    "sLengthMenu":   "Mostrar _MENU_ registros",
			                    "sZeroRecords":  "Não foram encontrados resultados",
			                    "sInfo":         "Exibindo de _START_ a _END_ de _TOTAL_ registros",
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
			                iDisplayLength: ' . $this->_pageSize . ', 
			                sPaginationType: "text_only", 
			                "bLengthChange": false, 
			                "bSort": false, 
			                "fnDrawCallback": function() {
			                    updateTable($(this));
			                },
			                "bProcessing": true,
			                "bServerSide": true,
			                "sAjaxSource": "' . $this->_ajaxUrl . '"
			            }).fnSetFilteringDelay();
			        });
			    </script>';
    	
    	$this->_html .= $html;
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
}
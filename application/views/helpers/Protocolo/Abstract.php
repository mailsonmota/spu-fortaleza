<?php
abstract class Zend_View_Helper_Protocolo_Abstract extends Zend_View_Helper_Abstract
{
    const TABLE_CLASS = 'grid';
    const TABLE_ROW_EVEN_CLASS = 'even';
    const TABLE_ROW_ODD_CLASS = 'odd';
    const INDENT_MARKUP = '&nbsp;&nbsp;&nbsp;&nbsp;';
    const LEVEL_SEPARATOR = ' / ';
    
    protected $_options;
    protected $_protocolos;
    protected $_html;
    
    public function __construct(array $protocolos = array(), array $options = array())
    {
        $this->_html = '';
        $this->_options = $options;
        $this->_protocolos = $protocolos;
        
        $this->_prepare();
        
        return $this;
    }
    
    public function render()
    {
        return $this->_html;
    }
    
    protected function _prepare()
    {
        $this->_prepareHeader();
        $this->_prepareFooter();
        $this->_prepareBody();
    }
    
    protected function _prepareHeader()
    {
        $html  = '<table class="' . self::TABLE_CLASS . '" summary="Lista de Protocolos">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= $this->_getBeforeHeaderColumns();
        $html .= '<th>Nome</th>';
        $html .= $this->_getAfterHeaderColumns();
        $html .= '</tr>';
        $html .= '</thead>';
        
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
        $html .= '<tfoot>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $this->_getNumberOfColumns() . '">';
        $html .= 'Exibindo ' . $this->_getTotalOfRecords() . ' registros.';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';
        
        $this->_html .= $html;
    }
    
    protected function _getNumberOfColumns()
    {
        return 1;
    }
    
    protected function _getTotalOfRecords()
    {
        return count($this->_protocolos);
    }
    
    protected function _prepareBody()
    {
        $html  = '';
        $html .= '<tbody>';
        $i = 0;
        foreach ($this->_protocolos as $protocolo) {
            $rowClass = (++$i%2) ? 'even' : 'odd';
            $path = str_replace("/", self::LEVEL_SEPARATOR, $protocolo->path);
            $indentation = str_repeat(self::INDENT_MARKUP, $protocolo->nivel - 1);
            $name = ($protocolo->nivel > 1) ? $indentation . '<span class="indentedName">' . $path . '</span>' : $path;
            
            $html .= '<tr class="' . $rowClass . '">';
            $html .= $this->_getBeforeBodyColumns($protocolo);
            $html .= '<td>' . $name . '</td>';
            $html .= $this->_getAfterBodyColumns($protocolo);
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
        $this->_html .= $html;
    }
    
    protected function _getBeforeBodyColumns($protocolo)
    {
        return '';
    }
    
    protected function _getAfterBodyColumns($protocolo)
    {
        return '';
    }
}
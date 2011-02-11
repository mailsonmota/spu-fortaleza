<?php
/**
 * SimpleDataTable
 * 
 * Component to create a table from a domain class
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @version 1.1
 */
class SimpleDataTable
{
    const DEFAULT_GRID_CLASS = 'grid';
    const DEFAULT_EVENROW_CLASS = 'even';
    const DEFAULT_ODDROW_CLASS = 'odd';
    const COLUMN_LINK = 'link';
    const COLUMN_CHECKBOX = 'checkbox';
    const COLUMN_RADIO = 'radio';
    
    protected $_html = '';
    protected $_options;
    protected $_data;
    protected $_dataColumns;
    
    public function __construct($data, $options)
    {
        $this->_setOptions($options);
        $this->_setData($data);
        $this->_setDataColumns($this->_getColumns());
    }
    
    protected function _getHtml()
    {
        return $this->_html;
    }
    
    protected function _setHtml($html)
    {
        $this->_html = $html;
    }
    
    protected function _setOptions($options)
    {
        $this->_options = $options;
    }
    
    protected function _getData()
    {
        return $this->_data;
    }
    
    protected function _setData($data)
    {
        $this->_data = $data;
    }
    
    protected function _getDataColumns()
    {
        return $this->_dataColumns;
    }
    
    protected function _setDataColumns($columns)
    {
        $this->_dataColumns = $columns;
    }
    
    public function render()
    {
        $this->_renderBeginningOfTheTable();
        $this->_renderHeader();
        $this->_renderFooter();
        $this->_renderBody();
        $this->_renderEndOfTheTable();
        
        return $this->_getHtml();
    }
    
    protected function _renderBeginningOfTheTable()
    {
        $tableClass = ($this->_getTableClass()) ? 'class="' . $this->_getTableClass() . '"' : '';
        
        $html = "<table $tableClass summary=\"data grid\" id=\"datagrid\">";
        $this->_addHtml($html);
    }
    
    protected function _renderHeader()
    {
        if ($this->_hasHeader()) {
            $columns = $this->_renderHeaderColumns();
            $html = "<thead><tr>$columns</tr></thead>";
            $this->_addHtml($html);
        }
    }
    
    protected function _hasHeader()
    {
        return ($this->_getOption('header') !== false);
    }
    
    protected function _getTableClass()
    {
        return ($this->_getOption('tableClass') !== null) ? 
            $this->_getOption('tableClass') : 
            self::DEFAULT_GRID_CLASS;
    }
    
    protected function _getOption($option)
    {
        return (is_array($this->_options) and isset($this->_options[$option])) ? $this->_options[$option] :null;
    }
    
    protected function _renderHeaderColumns()
    {
        $columns = $this->_getHeaderColumns();
        $html = '';
        foreach ($columns as $column) {
            $html .= "<th>$column</th>";
        }
        
        return $html;
    }
    
    protected function _getHeaderColumns()
    {
        return ($this->_getOption('headerColumns')) ? $this->_getOption('headerColumns') : $this->_getDataColumns();
    }
    
    protected function _getColumns()
    {
        return ($this->_getOption('columns')) ? $this->_getOption('columns') : $this->_getColumnsFromData();
    }
    
    protected function _getColumnsFromData()
    {
        $firstRow = $this->_getFirstRowFromData();
        $columns = array();
        foreach ($firstRow as $key => $value) {
            $columns[] = $key;
        }
        
        return $columns;
    }
    
    protected function _getFirstRowFromData()
    {
        $data = $this->_getData();
        return $data[0];
    }
    
    protected function _renderFooter()
    {
        if ($this->_hasFooter()) {
            $colspan = 'colspan="' . $this->_getNumberOfColumns() . '"';
            $numberOfRecords = $this->_getNumberOfRecords();
            $html = "<tfoot><tr><td $colspan><em>Exibindo $numberOfRecords registros.</em></td></tr></tfoot>";
            
            $this->_addHtml($html);
        }
    }
    
    protected function _hasFooter()
    {
        return ($this->_getOption('footer') !== false);
    }
    
    protected function _getNumberOfColumns()
    {
        return count($this->_getColumns());
    }
    
    protected function _getNumberOfRecords()
    {
        return count($this->_getData());
    }
    
    protected function _renderBody()
    {
        $bodyRows = $this->_renderBodyRows();
        $html = ($bodyRows) ? "<tbody>$bodyRows</tbody>" : '';
        
        $this->_addHtml($html);
    }
    
    protected function _renderBodyRows()
    {
        $html = "";
        $rowNumber = 0;
        foreach ($this->_getData() as $row) {
            $rowClass = ($rowNumber++ % 2) ? self::DEFAULT_ODDROW_CLASS: self::DEFAULT_EVENROW_CLASS;
            
            $html .= "<tr class=\"$rowClass\">";
            foreach ($this->_getDataColumns() as $column) {
                $html .= $this->_renderColumn($row, $column);
            }
            $html .= "</tr>";
        }
        
        return $html;
    }
    
    protected function _renderColumn($row, $column)
    {
        $html = '<td>';
        if ($this->_isTextColumn($column)) {
            $html .= $row->$column;
        } elseif ($this->_isLinkColumn($column)) {
            $html .= $this->_createLinkMarkup($column['url'] . $row->$column['paramValue'], $column['title']);
        } elseif ($this->_isCheckboxColumn($column)) {
            $html .= $this->_createCheckboxMarkup($column['name'] . '[]', $row->$column['paramValue']);
        } elseif ($this->_isRadioColumn($column)) {
            $html .= $this->_createRadioMarkup($column['name'] . '[]', $row->$column['paramValue']);
        }
        $html .= '</td>';
        
        return $html;
    }
    
    protected function _isTextColumn($column)
    {
        return !is_array($column);
    }
    
    protected function _isLinkColumn($column)
    {
        return ($this->_getColumnType($column) == self::COLUMN_LINK);
    }
    
    protected function _createLinkMarkup($href, $title)
    {
        return "<a href=\"$href\">$title</a>";
    }
    
    protected function _isCheckboxColumn($column)
    {
        return ($this->_getColumnType($column) == self::COLUMN_CHECKBOX);
    }
    
    protected function _createCheckboxMarkup($name, $value)
    {
        return "<input type=\"checkbox\" name=\"$name\" value=\"$value\"/>";
    }
    
    protected function _isRadioColumn($column)
    {
        return ($this->_getColumnType($column) == self::COLUMN_RADIO);
    }
    
    protected function _createRadioMarkup($name, $value)
    {
        return "<input type=\"radio\" name=\"$name\" value=\"$value\"/>";
    }
    
    protected function _getColumnType($column)
    {
        $columnType = (isset($column['type'])) ? $column['type'] : null;
        return $columnType;
    }
    
    protected function _renderEndOfTheTable()
    {
        $html = "</table>";
        $this->_addHtml($html);
    }
    
    protected function _addHtml($html)
    {
        $this->_html .= $html;
    }
    
    public function addActionColumn($title, $url, $paramValue = null)
    {
       $this->_addDataColumn(array('type' => self::COLUMN_LINK, 
                                   'title' => $title, 
                                   'url' => $url, 
                                   'paramValue' => $paramValue));
    }
    
    public function addCheckboxColumn($name, $paramValue) 
    {
        $this->_addColumnToTheBeginningOfTheArray(array('type' => self::COLUMN_CHECKBOX, 
                                                        'name' => $name, 
                                                        'paramValue' => $paramValue));
    }
    
    public function addRadioColumn($name, $paramValue) 
    {
        $this->_addColumnToTheBeginningOfTheArray(array('type' => self::COLUMN_RADIO, 
                                                        'name' => $name, 
                                                        'paramValue' => $paramValue));
    }
    
    protected function _addDataColumn($value)
    {
        $this->_dataColumns[] = $value;
    }
    
    protected function _addColumnToTheBeginningOfTheArray($value)
    {
        array_unshift($this->_dataColumns, $value);
    }
}
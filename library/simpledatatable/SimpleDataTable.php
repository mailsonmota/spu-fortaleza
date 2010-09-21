<?php
class SimpleDataTable
{
    const DEFAULT_GRID_CLASS = 'grid';
    const DEFAULT_EVENROW_CLASS = 'even';
    const DEFAULT_ODDROW_CLASS = 'odd';
    protected $_html = '';
    protected $_options;
    protected $_data;
    protected $_dataColumns;
    
    public function SimpleDataTable($data, $options)
    {
        $this->_setOptions($options);
        $this->_setData($data);
        $this->_setDataColumns($this->_getColumns());
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
        $tableClass = ($this->_getTableClass()) ? 
                'class="' . $this->_getTableClass() . '"' : 
                '';
        
        $html = "<table $tableClass>";
        $this->_addHtml($html);
    }
    
    protected function _renderHeader()
    {
        if ($this->_hasHeader()) {
            $columns = $this->_renderHeaderColumns();
            
            $html = "
                    <thead>
                        <tr>$columns</tr>
                    </thead>
            ";
            
            $this->_addHtml($html);
        }
    }
    
    protected function _hasHeader()
    {
        return ($this->_getOption('header') !== false);
    }
    
    protected function _getTableClass()
    {
        return ($this->_getOption('tableClass')) ? 
            $this->_getOption('tableClass') : 
            self::DEFAULT_GRID_CLASS;
    }
    
    protected function _getOption($option)
    {
        return (is_array($this->_options) and isset($this->_options[$option])) ? 
            $this->_options[$option] : 
            null;
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
        return ($this->_getOption('headerColumns')) ? 
            $this->_getOption('headerColumns') : 
            $this->_getDataColumns();
    }
    
    protected function _getColumns()
    {
        return ($this->_getOption('columns')) ? 
            $this->_getOption('columns') : 
            $this->_getColumnsFromData();
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
            $html = "
                <tfoot>
                    <tr $colspan>
                        <td><em>Exibindo $numberOfRecords registros.</em></td>
                    </tr>
                </tfoot>
            ";
            
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
        $html = "<tbody>$bodyRows</tbody>";
        
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
                if (!is_array($column)) {
                    $html .= "<td>" . $row->$column . "</td>";
                } else {
                    $html .= '<td><a href="' . $column['url'] . $row->$column['paramValue'] . '">' . $column['title'] . '</a></td>'; 
                }
            }
            $html .= "</tr>";
        }
        
        return $html;
    }
    
    protected function _renderEndOfTheTable()
    {
        $html = "</table>";
        $this->_addHtml($html);
    }
    
    protected function _getHtml()
    {
        return $this->_html;
    }
    
    protected function _setHtml($html)
    {
        $this->_html = $html;
    }
    
    protected function _addHtml($html)
    {
        $this->_html .= $html;
    }
    
    public function addActionColumn($title, $url, $paramValue = null)
    {
       $this->_addDataColumn(array('title' => $title, 'url' => $url, 'paramValue' => $paramValue));
    }
    
    protected function _addDataColumn($value)
    {
        $this->_dataColumns[] = $value;
    }
}
?>
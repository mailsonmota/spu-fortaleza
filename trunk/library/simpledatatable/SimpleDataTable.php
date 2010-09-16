<?php
class SimpleDataTable
{
    const DEFAULT_GRID_CLASS = 'grid';
    const DEFAULT_EVENROW_CLASS = 'even';
    const DEFAULT_ODDROW_CLASS = 'odd';
    protected $_html = '';
    protected $_options;
    protected $_data;
    
    public function SimpleDataTable($data, $options)
    {
        $this->_setOptions($options);
        $this->_setData($data);
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
            $this->_getColumns();
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
            $html = "
                <tfoot>
                    <tr>
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
            $rowClass = ($rowNumber++ % 2) ? 
                'class="' . self::DEFAULT_ODDROW_CLASS . '"' : 
                'class="' . self::DEFAULT_EVENROW_CLASS . '"';
             
            $html .= "<tr $rowClass>";
            foreach ($this->_getColumns() as $column) {
                $html .= "<td>" . $row->$column . "</td>";
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
}
?>
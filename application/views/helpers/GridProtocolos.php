<?php
class Zend_View_Helper_GridProtocolos extends Zend_View_Helper_Abstract
{
	const TABLE_CLASS = 'grid';
	const TABLE_ROW_EVEN_CLASS = 'even';
	const TABLE_ROW_ODD_CLASS = 'odd';
	const INDENT_MARKUP = '&nbsp;&nbsp;&nbsp;&nbsp;';
	const LEVEL_SEPARATOR = ' / ';
	
	protected $_options;
	protected $_protocolos;
	protected $_html;
	
	public function gridProtocolos(array $protocolos, array $options = array())
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
	    if ($this->_hasInputColumn()) {
            $html .= '<th></th>';
        }
		$html .= '<th>Nome</th>';
		if ($this->_hasActionColumn()) {
			$html .= '<th>' . $this->_getActionColumnHeader() . '</th>';
		}
		$html .= '</tr>';
		$html .= '</thead>';
		
		$this->_html .= $html;
	}
	
    protected function _hasInputColumn()
    {
        return (isset($this->_options['inputColumn'])) ? true : false;
    }
	
	protected function _hasActionColumn()
	{
		return (isset($this->_options['actionColumn'])) ? true : false;
	}
	
	protected function _getActionColumnHeader()
	{
		$header = '';
		if (isset($this->_options['actionColumn']['header'])) {
			$header = $this->_options['actionColumn']['header'];
		}
		
		return $header;
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
		$columns = 1;
		if ($this->_hasInputColumn()) {
			$columns++;
		}
        if ($this->_hasActionColumn()) {
            $columns++;
        }
		return $columns;
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
			if ($this->_hasInputColumn()) {
				$html .= '<td>' . $this->_renderInputColumn($protocolo) . '</td>';
			}
			$html .= '<td>' . $name . '</td>';
			if ($this->_hasActionColumn()) {
				$html .= '<td>' . $this->_renderActionColumn($protocolo) . '</td>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		
		$this->_html .= $html;
	}
	
    protected function _renderInputColumn($protocolo)
    {
        $html = '<input type="' . $this->_getInputColumnType() . '" 
                        name="' . $this->_getInputColumnName() . '" 
                        value="' . $this->_getInputColumnValue($protocolo) . '"/>';
        
        return $html;
    }
    
    protected function _getInputColumnType()
    {
        $type = '';
        if (isset($this->_options['inputColumn']['type'])) {
            $type = $this->_options['inputColumn']['type'];
        }
        
        return $type;
    }
    
    protected function _getInputColumnName()
    {
        $name = '';
        if (isset($this->_options['inputColumn']['name'])) {
            $name = $this->_options['inputColumn']['name'];
        }
        
        return $name;
    }
    
    protected function _getInputColumnValue(Protocolo $protocolo)
    {
    	$method = $this->_getInputColumnMethod();
        return $protocolo->$method;
    }
    
    protected function _getInputColumnMethod()
    {
        $method = '';
        if (isset($this->_options['inputColumn']['method'])) {
            $method = $this->_options['inputColumn']['method'];
        }
        
        return $method;
    }
	
	protected function _renderActionColumn(Protocolo $protocolo)
	{
		$html = '<a href="' . $this->_getActionColumnUrl($protocolo) . '">' . $this->_getActionColumnTitle() . '</a>';
		return $html;
	}
	
    protected function _getActionColumnUrl(Protocolo $protocolo)
    {
        $method = $this->_getActionColumnMethod();
        return $this->_getActionColumnBaseUrl() . '/' . $method . '/' . $protocolo->$method;
    }
	
    protected function _getActionColumnBaseUrl()
    {
    	$baseUrl = '';
        if (isset($this->_options['actionColumn']['baseUrl'])) {
            $baseUrl = $this->_options['actionColumn']['baseUrl'];
        }
        
        return $baseUrl;
    }
    
    protected function _getActionColumnMethod()
    {
        $method = '';
        if (isset($this->_options['actionColumn']['method'])) {
            $method = $this->_options['actionColumn']['method'];
        }
        
        return $method;
    }
	
	protected function _getActionColumnTitle()
	{
		$title = '';
        if (isset($this->_options['actionColumn']['title'])) {
            $title = $this->_options['actionColumn']['title'];
        }
        
        return $title;
	}
}
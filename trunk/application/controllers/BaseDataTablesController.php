<?php
class BaseDataTablesController extends BaseAuthenticatedController
{
	const DEFAULT_PAGE_SIZE = 20;
	
	protected $_rows = array();
	protected $_total = 0;
	
    protected function _getPageSize()
    {
        $pageSize = self::DEFAULT_PAGE_SIZE;
        if ($this->getRequest()->getParam('iDisplayLength', null) AND 
             $this->getRequest()->getParam('iDisplayLength') != '-1') {
             $pageSize = $this->getRequest()->getParam('iDisplayLength');
        }
        
        return $pageSize;
    }
	
	protected function _getOffset()
	{
		return $this->getRequest()->getParam('iDisplayStart', 0);
	}
	
    protected function _getSearch()
    {
        $search = '';
        if ($this->getRequest()->getParam('sSearch', null) AND 
            $this->getRequest()->getParam('sSearch') != '') {
            $search = $this->getRequest()->getParam('sSearch');
        }
        
        return $search;
    }
    
	protected function _getEcho()
	{
		return $this->getRequest()->getParam('sEcho', null);
	}
	
	protected function _getTotal()
	{
		return ($this->_total) ? $this->_total : count($this->_rows);
	}
	
	protected function _getOutput()
    {
        $output = '{';
        $output .= '"sEcho": ' . intval($this->_getEcho()) . ', ';
        $output .= '"iTotalRecords": ' . $this->_getTotal() . ', ';
        $output .= '"iTotalDisplayRecords": ' . $this->_getTotal() . ', ';
        $output .= '"aaData": [ ';
        foreach ($this->_rows as $row) {
            $output .= "[";
            
            foreach ($row as $key=>$value) {
            	$value = str_replace(array('"', "\n", "\r"), array('\\"', "\\n", "\\n"), $value);
            	$output .= '"' . $value . '",';
            }
            
            /*
             * Optional Configuration:
             * If you need to add any extra columns (add/edit/delete etc) to the table, that aren't in the
             * database - you can do it here
            */
        
            $output = substr_replace( $output, "", -1 );
            $output .= "],";
        }
        
        $output = substr_replace( $output, "", -1 );
        $output .= ']'; //Closing aaData
        $output .= '}';
    
        return $output;
    }
    
    protected function _getJsonErrorRow($e)
    {
    	//echo $e->getMessage();
    	return array();
    }
}
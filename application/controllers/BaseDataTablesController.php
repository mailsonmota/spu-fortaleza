<?php
class BaseDataTablesController extends BaseAuthenticatedController
{
    const DEFAULT_PAGE_SIZE = 20;
    
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
        if ($this->getRequest()->getParam('sSearch', null) AND $this->getRequest()->getParam('sSearch') != '') {
            $search = $this->getRequest()->getParam('sSearch');
        }
        
        return $search;
    }
    
    protected function _getEcho()
    {
        return $this->getRequest()->getParam('sEcho', 0);
    }
    
    protected function _getTotal()
    {
        return ($this->_total) ? $this->_total : 0;
    }
    
    public function postDispatch()
    {
    	$this->view->echo = $this->_getEcho();
    	$this->view->totalRecords = $this->_getTotal();
    	$this->view->totalDisplayRecords = $this->_getTotal();
    }
    
    protected function _getJsonErrorRow($e)
    {
        //echo $e->getMessage();
        return array();
    }
}
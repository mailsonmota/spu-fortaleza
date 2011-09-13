<?php
require_once 'Paginator/Array.php';
class Zend_Controller_Action_Helper_Paginator extends Zend_Controller_Action_Helper_Abstract
{
	public function getFromArray($array)
	{
		$paginator = new Zend_Controller_Action_Helper_Paginator_Array($array);
		$paginator->setCurrentPageNumber($this->_actionController->getRequest()->getParam('page', 1));
        
        return $paginator;
	}
	
	public function direct()
	{
		return $this;
	}
	
	public function paginate($array)
	{
		return $this->getFromArray($array);
	}
	
	public function getPageSize()
	{
		return 10;
	}
	
	public function getOffset()
	{
		return ($this->getRequest()->getParam('page')) ? 
			(($this->getRequest()->getParam('page') - 1) * $this->getPageSize()) : 
			0;
	}
}
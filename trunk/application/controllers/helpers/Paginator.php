<?php
/**
 * @see Zend_Controller_Action_Helper_Paginator_Array
 */
require_once 'Paginator/Array.php';

/**
 * Action Helper para realizar a paginação dos registros do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Zend_Controller_Action_Helper_Abstract
 */
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
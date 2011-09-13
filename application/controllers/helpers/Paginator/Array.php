<?php
class Zend_Controller_Action_Helper_Paginator_Array implements Countable, IteratorAggregate
{
	protected $_currentPageNumber = 1;
	protected $_pageSize = 10;
	protected $_items = array();
	
	public function __construct(array $items)
	{
		$this->_items = $items;		
	}
	
	public function getCurrentPageNumber()
	{
		return $this->_currentPageNumber;
	}
	
	public function setCurrentPageNumber($value)
	{
		$this->_currentPageNumber = $value;
	}
	
	public function next()
	{
		return (count($this->_items) == $this->_pageSize) ? $this->_currentPageNumber + 1 : false;
	}
	
	public function previous()
	{
		return ($this->_currentPageNumber > 1) ? $this->_currentPageNumber - 1 : false;
	}
	
	public function hasPages()
	{
		return ($this->next() OR $this->previous());
	}
	
	/**
	* Returns a foreach-compatible iterator.
	*
	* @return Traversable
	*/
	public function getIterator()
	{
		return new ArrayIterator($this->_items);
	}
	
	public function count()
	{
		return count($this->_items);
	}
}
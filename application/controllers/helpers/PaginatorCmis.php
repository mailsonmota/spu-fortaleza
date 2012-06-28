<?php

class Zend_Controller_Action_Helper_PaginatorCmis extends Zend_Controller_Action_Helper_Abstract
{

    public $_skipCount;
    public $_maxItems;
    public $_pagina;
    public $_offSet;

    const SKIP_COUNT = 0;
    const MAX_ITEMS = 10;

    public function direct($maxItems = self::MAX_ITEMS, $skipCount = self::SKIP_COUNT)
    {
        $this->setMaxItems($maxItems);
        $this->setSkipCount($skipCount);
        $this->setPagina();
        $this->setOffSet();

        return $this;
    }

    public function setSkipCount($skipCount)
    {
        $this->_skipCount = $skipCount;
    }

    public function getSkipCount()
    {
        return $this->_skipCount;
    }

    public function setMaxItems($maxItems)
    {
        $this->_maxItems = $maxItems;
    }

    public function getMaxItems()
    {
        return $this->_maxItems;
    }

    public function setPagina()
    {
        $this->_pagina = $this->getRequest()->getParam('pagina', 1);
    }

    public function getPagina()
    {
        return $this->_pagina;
    }
    
    public function setOffSet()
    {
        $this->_offSet = ($this->getPagina() < 2 ? 0 : ($this->getPagina()-1) * $this->getMaxItems());
    }

    public function getOffSet()
    {
        return $this->_offSet;
    }

}
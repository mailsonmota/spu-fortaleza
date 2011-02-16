<?php
require_once 'Proxy.php';
class Zend_View_Helper_FieldList extends Zend_View_Helper_Proxy
{
    public function fieldList()
    {
        return $this;
    }
}
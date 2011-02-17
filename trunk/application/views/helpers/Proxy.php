<?php
/**
 * Proxy helper for retrieving protocolo helpers and forwarding calls
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_View_Helper_Proxy extends Zend_View_Helper_Abstract
{
    /**
     * Contains references to proxied helpers
     *
     * @var array
     */
    protected $_helpers = array();

    public function proxy()
    {
        return $this;
    }

    public function __call($method, array $arguments = array())
    {
        // check if call should proxy to another helper
        if (!method_exists($this, $method) AND $method != strtolower($this->_getProxyName()) AND $helper = $this->findHelper($method)) {
            return call_user_func_array(array($helper, $method), $arguments);
        }

        return $this;
    }

    public function findHelper($proxy)
    {
        if (isset($this->_helpers[$proxy])) {
            return $this->_helpers[$proxy];
        }
        
        $helperFileName = ucwords($proxy);
        require_once $this->_getProxyName() . "/$helperFileName.php";
        $helperClassName = $this->_getProxyFullName() . '_' . $helperFileName;
        $helper = new $helperClassName();
        
        $this->_helpers[$proxy] = $helper;

        return $helper;
    }

    private function _getProxyName()
    {
        return $this->_getClassFromNamespace($this->_getProxyFullName());
    }
    
    private function _getProxyFullName()
    {
        return get_class($this);
    }
    
    private function _getClassFromNamespace($namespace)
    {
        return substr(strrchr($namespace, '_'), 1);
    }
}
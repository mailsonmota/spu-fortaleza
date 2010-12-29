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

    /**
     * Helper entry point
     *
     * @param  Zend_Navigation_Container $container  [optional] container to
     *                                               operate on
     * @return Zend_View_Helper_Protocolo           fluent interface, returns
     *                                               self
     */
    public function proxy()
    {
        return $this;
    }

    /**
     * Magic overload: Proxy to other navigation helpers or the container
     *
     * Examples of usage from a view script or layout:
     * <code>
     * // proxy to Menu helper and render container:
     * echo $this->navigation()->menu();
     *
     * // proxy to Breadcrumbs helper and set indentation:
     * $this->navigation()->breadcrumbs()->setIndent(8);
     *
     * // proxy to container and find all pages with 'blog' route:
     * $blogPages = $this->navigation()->findAllByRoute('blog');
     * </code>
     *
     * @param  string $method             helper name or method name in
     *                                    container
     * @param  array  $arguments          [optional] arguments to pass
     * @return mixed                      returns what the proxied call returns
     * @throws Zend_View_Exception        if proxying to a helper, and the
     *                                    helper is not an instance of the
     *                                    interface specified in
     *                                    {@link findHelper()}
     * @throws Zend_Navigation_Exception  if method does not exist in container
     */
    public function __call($method, array $arguments = array())
    {
        // check if call should proxy to another helper
        if (!method_exists($this, $method) AND $method != strtolower($this->_getProxyName()) AND $helper = $this->findHelper($method)) {
            return call_user_func_array(array($helper, $method), $arguments);
        }

        return $this;
        
        // default behaviour: proxy call to container
        //return call_user_func_array(array($this->getContainer(), $method), $arguments);
    }

    /**
     * Returns the helper matching $proxy
     *
     * The helper must implement the interface
     * {@link Zend_View_Helper_Navigation_Helper}.
     *
     * @param string $proxy                        helper name
     * @param bool   $strict                       [optional] whether
     *                                             exceptions should be
     *                                             thrown if something goes
     *                                             wrong. Default is true.
     * @return Zend_View_Helper_Navigation_Helper  helper instance
     * @throws Zend_Loader_PluginLoader_Exception  if $strict is true and
     *                                             helper cannot be found
     * @throws Zend_View_Exception                 if $strict is true and
     *                                             helper does not implement
     *                                             the specified interface
     */
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
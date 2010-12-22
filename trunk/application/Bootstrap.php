<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
    	$libraryPath = '../application/library';
        Zend_Loader::loadClass('Loader', "$libraryPath/Spu");
        Zend_Loader::loadClass('BaseController', '../application/controllers');
        Zend_Loader::loadClass('SimpleDataTable', "$libraryPath/SimpleDataTable");
        Zend_Loader::loadClass('ErrorPlugin');
        Zend_Loader::loadClass('AuthPlugin');
        Zend_Loader::loadClass('AuthAdapter');
        Zend_Loader::loadClass('AlfrescoApiException', "$libraryPath/spu/exceptions");
    }
    
    protected function _initControllers(array $options = array())
    {
        $this->bootstrap('FrontController'); 
        $front = $this->getResource('FrontController');
        
        // Timezone
        date_default_timezone_set('America/Fortaleza');
        setlocale(LC_TIME, 'pt_BR.UTF-8');
        
        $front->registerPlugin(new ErrorPlugin());
        $front->throwExceptions(false);
        
        $auth = Zend_Auth::getInstance();
        $front->setParam('auth', $auth);
        $front->registerPlugin(new AuthPlugin($auth));
    }
    
    protected function _initDoctype() 
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        
        // Url Base da Aplicação (Pasta Public)
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        if (!$baseUrl) {
            $baseUrl = rtrim(preg_replace('/([^\/]*)$/', '', $_SERVER['PHP_SELF']), '/\\');
        }
        
        $view->doctype('XHTML1_STRICT');
        $this->initTitle($view, $baseUrl);
        $this->initFavicon($view, $baseUrl);
        $this->initCss($view, $baseUrl);
        $this->initJs($view, $baseUrl);
    }
    
    protected function initTitle(Zend_View $view, $baseUrl)
    {
        $view->headTitle('SPU - Sistema de Protocolo Único');
    }
    
    protected function initFavicon(Zend_View $view, $baseUrl)
    {
        $view->headLink(array('rel' => 'icon', 'href' => $baseUrl . '/favicon.ico'));
        $view->headLink(array('rel' => 'shortcut icon', 'href' => $baseUrl . '/favicon.ico'));
    }
    
    protected function initCss(Zend_View $view, $baseUrl)
    {
        $view->headLink()->appendStylesheet($baseUrl . '/css/reset.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/forms.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/facebox.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/datePicker.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/plupload.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/dataTables.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/estilo.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/tema.css');
    }
   
    protected function initJs(Zend_View $view, $baseUrl)
    {
        $jsPath = $baseUrl . '/js/';
        $pluginsPath = $jsPath . 'plugins/';
        
        $view->headScript()->appendFile($pluginsPath . 'jquery-1.4.2.min.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.meio.mask-1.1.3.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.validate.min.js');
        $view->headScript()->appendFile($pluginsPath . 'date.js');
        $view->headScript()->appendFile($pluginsPath . 'date_pt-br.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.datePicker.js');
        $view->headScript()->appendFile($pluginsPath . 'facebox.js');
        $view->headScript()->appendFile($pluginsPath . 'tableRowCheckboxToggle.js');
        $view->headScript()->appendFile($pluginsPath . '/dataTables/jquery.dataTables.min.js');
        $view->headScript()->appendFile($pluginsPath . '/dataTables/textOnlyPagination.js');
        $view->headScript()->appendFile($jsPath . 'funcoes.js');
    }
    
    protected function _initNavigation()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
    }
}
<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        Zend_Loader::loadClass('BaseController', '../application/controllers');
        Zend_Loader::loadClass('AuthAdapter');
        Zend_Loader::loadClass('XSDCreator');

        $autoloader = new Zend_Application_Module_Autoloader(
            array(
                'namespace' => '',
                'basePath'  => APPLICATION_PATH,
                'resourceTypes' => array(
                    'alfresco' => array('path' => 'library/Alfresco/', 'namespace' => 'Alfresco'),
                    'models' => array('path' => 'models/', 'namespace' => 'Application_Model'),
                    'spu'      => array('path' => 'library/Spu/', 'namespace' => 'Spu')
            	)
            )
        );
        return $autoloader;
    }

    public function _initLoadParamsApplication()
    {
        $init = new Zend_Config_Ini('../application/configs/application.ini', APPLICATION_ENV);

        $registry = Zend_Registry::getInstance();
        $registry->set("baseDownload", $init->alfresco->basedownload);
        $registry->set("aposentadorias", $init->alfresco->aposentadorias);
        $registry->set("baseUrlAlfresco", $init->alfresco->url);
        $registry->set("groupSearch", $init->alfresco->group->search);
        $registry->set("totem", $init->totem);
        $registry->set("manutencao", $init->manutencao);
        $registry->set("blog", $init->blog);
        $registry->set("excludeNodeRefs", $this->_excludeNodeRefs());
    }

    protected function _initControllers(array $options = array())
    {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');

        $front->registerPlugin(new Plugin_Error());
        $front->throwExceptions(false);

        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_SPU'));
        $front->registerPlugin(new Plugin_Auth($auth));
    }

    protected function _initTimezone()
    {
    	date_default_timezone_set('America/Fortaleza');
    	setlocale(LC_TIME, 'pt_BR.UTF-8');
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');

        $view->doctype('XHTML1_TRANSITIONAL');
    }

    protected function _initTitle()
    {
    	$this->bootstrap('view');
    	$view = $this->getResource('view');

        $view->headTitle('SPU - Sistema de Protocolo Único');
    }

    protected function _initFavicon()
    {
    	$baseUrl = $this->_getBaseUrl();

    	$this->bootstrap('view');
    	$view = $this->getResource('view');

        $view->headLink(array('rel' => 'icon', 'href' => $baseUrl . '/favicon.ico'));
        $view->headLink(array('rel' => 'shortcut icon', 'href' => $baseUrl . '/favicon.ico'));
    }

    protected function _getBaseUrl()
    {
    	$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

    	if (!$baseUrl) {
    		$baseUrl = rtrim(preg_replace('/([^\/]*)$/', '', $_SERVER['PHP_SELF']), '/\\');
    	}

    	return $baseUrl;
    }

    protected function _initCss()
    {
    	$baseUrl = $this->_getBaseUrl();

    	$this->bootstrap('view');
    	$view = $this->getResource('view');

        $view->headLink()->appendStylesheet($baseUrl . '/css/reset.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/forms.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/simple-modal.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/datePicker.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/jquery-ui-1.8.9.custom.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/estilo.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/print.css', array('media' => 'print'));
        $view->headLink()->appendStylesheet($baseUrl . '/css/tema.css');
        $view->headLink()->appendStylesheet($baseUrl . '/css/chosen.css');
    }

    protected function _initJs()
    {
    	$baseUrl = $this->_getBaseUrl();

    	$this->bootstrap('view');
    	$view = $this->getResource('view');

        $jsPath = $baseUrl . '/js/';
        $pluginsPath = $jsPath . 'plugins/';

        $view->headScript()->appendScript('var baseUrl="' . $baseUrl . '"');
        $view->headScript()->appendFile($pluginsPath . 'jquery-1.4.4.min.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.validate.min.js');
        $view->headScript()->appendFile($pluginsPath . 'date.js');
        $view->headScript()->appendFile($pluginsPath . 'date_pt-br.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.datePicker.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.simplemodal.1.4.1.min.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.elastic.js');
        $view->headScript()->appendFile($pluginsPath . 'input.deflate.plugin.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery.meio.mask.js');
        $view->headScript()->appendFile($pluginsPath . 'xsdForm.js');
        $view->headScript()->appendFile($pluginsPath . 'xsdForm-ui.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery-ui-1.8.9.custom.min.js');
        $view->headScript()->appendFile($pluginsPath . 'jquery-simulate.js');
        $view->headScript()->appendFile($pluginsPath . 'regex-mask-plugin.js');
        $view->headScript()->appendFile($pluginsPath . 'chosen.jquery.min.js');
        $view->headScript()->appendFile($jsPath . 'funcoes.js');
        $view->headScript()->appendFile($jsPath . 'xsdFormSpu.js');
        $view->headScript()->appendFile($jsPath . 'google.js');
    }

    protected function _initNavigation()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
    }
    
    public function _initCache()
    {
        $cacheManager = $this
                ->bootstrap('cachemanager')
                ->getResource('cachemanager');
        $cache = $cacheManager
                ->getCache('config');

        Zend_Registry::set('cache', $cache);
    }
    
    protected function _excludeNodeRefs()
    {
        return array(
            "233ec12f-c62d-48c1-84e9-ef3e35c0aa1c",
            "aec7b1e8-e754-4ff9-ab61-2c2bc31c29aa",
            "2e05632d-5507-417d-9f2d-6462a25ad584",
            "d2f1bb8f-e175-46a1-aa7e-1708b49d4a75",
            "22aa26a5-288c-409c-9203-3e7f81bcf722",
            "3b20dae5-9ee1-40bf-b553-00be4aaab1d2",
            "d9bf1f64-3b78-4502-868b-3fb64593b84d",
            "94989273-7f6a-4bae-a4f2-3cf051b386fe",
            "fae8e868-477d-435b-bc81-029526f81ceb",
            "f9a21aac-bd7e-45e4-acec-793dee50caf6",
            "cedf1a97-3e99-4d34-a2c4-73727d431dab",
            "4bc4ed51-890b-4064-b7bf-37ac817ae6d4",
            "d498078c-7a44-4c6b-84f4-40ce521cca88",
            "9186f6a3-732e-49bd-b0d9-be307d3f5862",
            "c34989e3-b06f-4864-95dd-ba865ecb1610",
            "2a817aca-6506-41bc-beea-febdc62d7b5c",
            "ead05353-c861-4cff-ba11-4b686340a923",
            "b3d33be7-1076-489f-9eb6-4a39a5f7c07a",
            "6dab857c-d98d-4c43-add5-edcfbf84cc16",
            "865d4776-5b61-43f7-ab2c-143f7f737241",
            "93c64dfe-0bfa-4fe7-816c-9cb3690adc16",
            "efebe529-1342-40a3-b85f-a4286bb3bacd",
            "e4005021-f939-46ca-9241-71329d91e6d7",
            "e135d53f-56b2-4bf3-b076-d652be9b3061",
            "3b343710-ac0a-4c28-a914-5391c1b60418",
            "44aecc32-5e30-4b36-a650-550c2ecfb6d4",
            "ec26d270-caf1-4921-b55b-9a0ae1ac907a",
            "a8b62fdb-e61b-45d2-9e14-27499e386a40",
            "424d5c2c-2504-462d-af9c-3a6091269315",
            "2e5f1264-d659-4fa3-abd5-507f3f28a474",
            "aa658547-70b5-4737-ae9f-a00576d9bf69",
            "73e547eb-7e2e-46b9-a59a-984af219ab14",
            "82bb081b-dc34-45b7-8697-2a79ff2bcc5b",
            "7df15ad8-1c3c-4235-aada-5047bbd1cc78",
            "f1079f7c-b3e3-4c41-88d7-77d63c09774b",
            "67f12441-0182-4903-b5db-086dd56d4d08",
            "77e8182c-0766-4320-a57c-e8b17dc41a40",
            "ea2069f8-f299-48bb-aa59-f2cd58cd551b",
            "c6dcea12-5132-4ee8-97d5-1a24e5ac0b76",
            "90eec255-3a72-49b3-8a3e-4f841f020b98",
            "24a09812-0ecf-4e45-8fe1-af482358cf02",
            "cfe8fe2a-fdda-4800-bbca-360483a17169",
            "01d186c1-5f5e-4554-a6bf-539d4327599d"
        );
    }
}
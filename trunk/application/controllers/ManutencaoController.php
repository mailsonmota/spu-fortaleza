<?php

class ManutencaoController extends BaseController
{

    private $_u;
    private $_pw;
    const NAME_CACHE = 'manutencao';

    public function init()
    {
        
    }

    public function indexAction()
    {
        $this->_helper->layout()->setLayout('basic');
    }

    public function onAction()
    {
        $this->_checkLogin();
        
        $this->_onSessionManutencao();
    }

    public function offAction()
    {
        $this->_checkLogin();
        
        $this->_offSessionManutencao();
        
        $this->_helper->redirector('index', 'index');
    }
    
    public function clearCacheAction()
    {
        $this->_checkLogin();
        
        $this->_getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
        
        $this->_helper->redirector('index', 'index');
    }

    private function _checkLogin()
    {
        $this->_u = $this->getRequest()->getParam('u', null);
        $this->_pw = $this->getRequest()->getParam('pw', null);

        $dadosLogin = Zend_Registry::get('manutencao');

        if ($this->_u != $dadosLogin->user || $this->_pw != $dadosLogin->senha)
            die("ACESSO NEGADO");
    }

    private function _onSessionManutencao()
    {
        if (($this->_getCache()->load(self::NAME_CACHE)) === false)
            $this->_getCache()->save(true, self::NAME_CACHE);
    }
    
    private function _offSessionManutencao()
    {
        if (($this->_getCache()->load(self::NAME_CACHE)) === true)
            $this->_getCache()->remove(self::NAME_CACHE);
    }
    
    private function _getCache()
    {
        return Zend_Registry::get('cache');
    }

}

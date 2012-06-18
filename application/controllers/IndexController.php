<?php
class IndexController extends BaseController
{
    public function indexAction()
    {
        if ($this->view->pessoa->isGuest()) {
            $this->setMessageForTheView('Você não tem acesso à nenhum setor de protocolo, portanto
                                  não poderá abrir ou tramitar processos.');
        }
    }
    
    public function clearCacheAction()
    {
        Zend_Registry::get('cache')->clean(Zend_Cache::CLEANING_MODE_ALL);
        echo '<pre>';
        var_dump("Cache Limpo!!!");
        echo '</pre>';
        die("---- DIE ----");
    }
}

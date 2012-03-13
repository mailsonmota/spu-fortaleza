<?php
class EnvolvidosAjaxController extends BaseAuthenticatedController
{
    public function envolvidoAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->manifestante = $this->_getManifestante($this->_filterCpfCnpj($this->_getSearchTerm()));
    }

    protected function _getManifestante($cpfCnpj)
    {
        $manifestanteService = new Spu_Service_Manifestante($this->_getTicketAdmin());
        return $manifestanteService->getManifestante($cpfCnpj);
    }

    protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }
    
    private function _filterCpfCnpj($val)
    {
        return strlen($val) != 18 ? str_replace(array(".","-"), "",$val) : $val;
    }
    
    private function _getBaseUrl()
    {
        $init = new Zend_Config_Ini('../application/configs/application.ini', APPLICATION_ENV);
        return $init->alfresco->url;
    }

    private function _getTicketAdmin()
    {
        $alfresco = new Alfresco_Rest_Login($this->_getBaseUrl() . "/service");
        $ticket = $alfresco->login("login", "senha");
        $alfresco->logout($ticket['ticket']);
        
        return $ticket['ticket'];
    }
}
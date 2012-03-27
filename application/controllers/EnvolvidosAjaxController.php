<?php
class EnvolvidosAjaxController extends BaseController
{
    public function envolvidoAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->manifestante = $this->_getManifestante($this->_getSearchTerm());
    }

    protected function _getManifestante($cpfCnpj)
    {
        $manifestanteService = new Spu_Service_Manifestante($this->getTicketSearch());
        return $manifestanteService->getManifestante($cpfCnpj);
    }

    protected function _getSearchTerm()
    {
        $cpfCnpj = $this->getRequest()->getParam('term', null);
        
        if (strlen($cpfCnpj) == 14)
            return str_replace(array(".", "-"), "", $cpfCnpj);
        
        return  $cpfCnpj;
    }
}
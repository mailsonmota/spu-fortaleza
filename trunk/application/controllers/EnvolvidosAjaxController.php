<?php
class EnvolvidosAjaxController extends BaseController
{
    public function envolvidoAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->manifestante = $this->_getManifestante(str_replace(array(".","-", "/"), "",$this->_getSearchTerm()));
    }

    protected function _getManifestante($cpfCnpj)
    {
        $manifestanteService = new Spu_Service_Manifestante($this->getTicketSearch());
        return $manifestanteService->getManifestante($cpfCnpj);
    }

    protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }
}
<?php
class ProtocolosController extends BaseController
{
    public function indexAction() {
    }

    public function editarAction()
    {
        $id = $this->_getIdFromUrl();

        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolo = $protocoloService->getProtocolo($id);
        
        $this->view->protocolo = $protocolo;
        $this->view->id = $protocolo->getId();
    }

    private function _getIdFromUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $id;
    }
}

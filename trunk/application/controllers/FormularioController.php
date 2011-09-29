<?php
class FormularioController extends BaseController
{
    public function contentAction()
    {
        $this->_helper->layout()->disableLayout();

        $assuntoId = $this->getRequest()->getParam('id');

        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $url = $arquivoService->getArquivoFormularioDownloadUrl($assuntoId);

        $this->view->result = $arquivoService->getContentFromUrl($url);
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');

        $this->view->id = $id;
    }
}

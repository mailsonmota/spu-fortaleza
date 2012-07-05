<?php

class ProcessosAjaxController extends BaseController
{

    public function init()
    {
        $this->_helper->layout->disableLayout();

        if (!$this->isPostAjax())
            die();
    }

    public function caixaEntradaAction()
    {
        $this->getRequest()->setParam('tramitacaoTipo', 'caixaentrada');
        $this->_forward('processos-cmis');
    }
    
    public function caixaAnaliseAction()
    {
        $this->getRequest()->setParam('tramitacaoTipo', 'caixaanalise');
        $this->_forward('processos-cmis');
    }

    public function processosCmisAction()
    {
        $this->view->tramitacaoTipo = $this->getRequest()->getParam('tramitacaoTipo');
    }

    public function processosGridAction()
    {
        $tramitacaoTipo = $this->getRequest()->getParam('tramitacaoTipo');
        
        $service = new Spu_Service_Tramitacao($this->getTicket());
        $buscaCmis = $service->getProcessosPaginatorCmis(
            $this->_helper->paginatorCmis()->getOffSet(),
            $this->_helper->paginatorCmis()->getMaxItems(),
            $tramitacaoTipo
        );

        if ($this->_helper->paginatorCmis()->getPagina() == 1)
            $this->view->totalDocumentos = $buscaCmis["totalItens"];

        $this->view->paginatorCmis = $buscaCmis["processos"];
    }

}
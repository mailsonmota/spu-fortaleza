<?php

require_once('BaseTramitacaoController.php');

class EnviadosController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->_getParam('mostrar') == 'true') {
            $this->view->mostrar = 'true';
            $service = new Spu_Service_Tramitacao($this->getTicket());
            $busca = $service->getCaixaEnviados(
                            $this->_helper->paginator()->getOffset(),
                            $this->_helper->paginator()->getPageSize(),
                            $this->view->q,
                            $this->view->assuntoId
            );
            $this->view->paginator = $this->_helper->paginator()->paginate($busca);
            $this->view->totalDocumentos = count($busca);
        }

        $this->view->q = urldecode($this->_getParam('q'));
        $this->view->tipoProcessoId = urldecode($this->_getParam('tipo-processo'));
        $this->view->assuntoId = urldecode($this->_getParam('assunto'));
        $this->view->tiposProcesso = $this->_getListaTiposProcesso();

        $service = new Spu_Service_Tramitacao($this->getTicket());
        $busca = $service->getCaixaEnviados(
            $this->_helper->paginator()->getOffset(), 
            $this->_helper->paginator()->getPageSize(), 
            $this->view->q, 
            $this->view->assuntoId
        );
        
        $this->view->paginator = $this->_helper->paginator()->paginate($busca);
        $this->view->totalDocumentos = count($busca);
    }

}
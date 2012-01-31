<?php

class TiposprocessoController extends BaseController
{

    public function indexAction()
    {
        $tipoProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
        $this->view->tiposprocesso = $tipoProcessoService->getTiposProcesso();
    }

    public function editarAction()
    {
        $id = $this->_getIdFromUrl();

        try {
            $tipoProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
            $tipoProcesso = $tipoProcessoService->getTipoProcesso($id);
            $listaTiposManifestante = $this->_getListaTiposManifestante();
            $listaTiposTramitacao = $this->_getListaTiposTramitacao();
            $listaTiposAbrangencia = $this->_getListaTiposAbrangencia();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectListaTiposProcesso();
        }

        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposManifestante = $listaTiposManifestante;
        $this->view->tiposManifestante = $this->_getTiposManifestanteTipoProcesso($tipoProcesso);
        $this->view->listaTiposTramitacao = $listaTiposTramitacao;
        $this->view->listaTiposAbrangencia = $listaTiposAbrangencia;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }

    private function _getIdFromUrl()
    {
        return $this->getRequest()->getParam('id');
    }

    protected function _getListaTiposTramitacao()
    {
        $tipoTramitacaoService = new Spu_Service_TipoTramitacao($this->getTicket());
        $tiposTramitacao = $tipoTramitacaoService->fetchAll();
        $listaTiposTramitacao = array();
        foreach ($tiposTramitacao as $tipoTramitacao) {
            $listaTiposTramitacao[$tipoTramitacao->id] = $tipoTramitacao->descricao;
        }

        if (count($listaTiposTramitacao) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de tramitação cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }

        return $listaTiposTramitacao;
    }

    protected function _getListaTiposAbrangencia()
    {
        $tipoAbrangenciaService = new Spu_Service_TipoAbrangencia($this->getTicket());
        $tiposAbrangencia = $tipoAbrangenciaService->fetchAll();
        $listaTiposAbrangencia = array();
        foreach ($tiposAbrangencia as $tipoAbrangencia) {
            $listaTiposAbrangencia[$tipoAbrangencia->id] = $tipoAbrangencia->descricao;
        }

        if (count($listaTiposAbrangencia) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de abrangência cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }

        return $listaTiposAbrangencia;
    }

    protected function _getListaTiposManifestante()
    {
        $tipoManifestanteService = new Spu_Service_TipoManifestante($this->getTicket());
        $tiposManifestante = $tipoManifestanteService->fetchAll();
        $listaTiposManifestante = array();
        foreach ($tiposManifestante as $tipoManifestante) {
            $listaTiposManifestante[$tipoManifestante->id] = $tipoManifestante->descricao;
        }

        if (count($listaTiposManifestante) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de abrangência cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }

        return $listaTiposManifestante;
    }

    protected function _getTiposManifestanteTipoProcesso($tipoProcesso)
    {
        $tiposManifestante = $tipoProcesso->tiposManifestante;
        $arrayTiposManifestante = array();
        foreach ($tiposManifestante as $tipoManifestante) {
            $arrayTiposManifestante[] = $tipoManifestante->id;
        }
        return $arrayTiposManifestante;
    }

    protected function _redirectListaTiposProcesso()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }

    public function assuntosAction()
    {
        $id = $this->_getIdFromUrl();

        $tipoProcessoService = new Spu_Service_TipoProcesso($this->getTicket());
        $tipoProcesso = $tipoProcessoService->getTipoProcesso($id);
        $assuntos = $this->_getAssuntos($id);

        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->assuntos = $assuntos;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }

    protected function _getAssuntos($tipoProcessoId)
    {
        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $assuntos = $assuntoService->getAssuntosPorTipoProcesso($tipoProcessoId);

        return $assuntos;
    }

    private function _mySort(&$obj, $prop)
    {
        uasort($obj, function($a, $b) use ($prop) {
                return strcmp($a->$prop, $b->$prop);
            });
    }

    public function assuntosAjaxAction()
    {
        $id = $this->_getIdFromUrl();
        $assuntos = $this->_getAssuntos($id);

        $this->_helper->layout()->disableLayout();
        $this->view->assuntos = $assuntos;
    }

}
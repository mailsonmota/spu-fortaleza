<?php
Loader::loadDao('TipoProcessoDao');
Loader::loadDao('TipoTramitacaoDao');
Loader::loadDao('TipoAbrangenciaDao');
Loader::loadDao('TipoManifestanteDao');
Loader::loadDao('AssuntoDao');
class TiposprocessoController extends BaseController
{
    public function indexAction()
    {
        $tipoProcessoDao = new TipoProcessoDao($this->getTicket());
        $this->view->lista = $tipoProcessoDao->getTiposProcesso();
    }
    
    public function editarAction()
    {
        $id = $this->_getIdFromUrl();
        
        try {
            $tipoProcessoDao = new TipoProcessoDao($this->getTicket());
            $tipoProcesso = $tipoProcessoDao->getTipoProcesso($id);
            $listaTiposTramitacao = $this->_getListaTiposTramitacao();
            $listaTiposAbrangencia = $this->_getListaTiposAbrangencia();
            $listaTiposManifestante = $this->_getListaTiposManifestante();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectListaTiposProcesso();
        }
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposTramitacao = $listaTiposTramitacao;
        $this->view->listaTiposAbrangencia = $listaTiposAbrangencia;
        $this->view->listaTiposManifestante = $listaTiposManifestante;
        $this->view->tiposManifestante = $this->_getTiposManifestanteTipoProcesso($tipoProcesso);
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }
    
    private function _getIdFromUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $id;
    }
    
    protected function _getListaTiposTramitacao()
    {
        $tipoTramitacaoDao = new TipoTramitacaoDao($this->getTicket());
        $tiposTramitacao = $tipoTramitacaoDao->fetchAll();
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
        $tipoAbrangenciaDao = new TipoAbrangenciaDao($this->getTicket());
        $tiposAbrangencia = $tipoAbrangenciaDao->fetchAll();
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
        $tipoManifestanteDao = new TipoManifestanteDao($this->getTicket());
        $tiposManifestante = $tipoManifestanteDao->fetchAll();
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
        
        $tipoProcessoDao = new TipoProcessoDao($this->getTicket());
        $tipoProcesso = $tipoProcessoDao->getTipoProcesso($id);
        $assuntoDao = new AssuntoDao($this->getTicket());
        $assuntos = $assuntoDao->getAssuntosPorTipoProcesso($id);
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->assuntos = $assuntos;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }   
}
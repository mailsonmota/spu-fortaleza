<?php
Loader::loadEntity('Processo');
Loader::loadEntity('Bairro');
Loader::loadEntity('Protocolo');
class AbrirprocessoController extends BaseController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector(
                'formulario', 
                $this->getController(), 
                'default',
                array('tipoprocesso' => $this->_getIdTipoProcessoPost())
            );
        }
        
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $listaTiposProcesso = $this->_getListaTiposProcesso();
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposProcesso = $listaTiposProcesso;
    }
    
    public function formularioAction()
    {
        try {
            $tipoProcesso = $this->_getTipoProcesso($this->_getIdTipoProcessoUrl());
            $listaTiposProcesso = $this->_getListaTiposProcesso();
            $listaAssuntos = $this->_getListaAssuntos($tipoProcesso);
            $listaBairros = $this->_getListaBairros();
            $listaTiposManifestante = $this->_getListaTiposManifestante($tipoProcesso);
            $listaPrioridades = $this->_getListaPrioridades();
            $listaProtocolos = $this->_getListaProtocolos();
            $listaOrigens = $this->_getListaOrigens();
            $listaProprietarios = $this->_getListaProprietarios();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEscolhaTipoProcesso();
        }
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            try {
            	$session = new Zend_Session_Namespace('aberturaProcesso');
            	$session->formDadosGeraisProcesso = $postData;
            	$this->_redirectFormularioEnvolvido();
            	/*Fluxo anterior, quando não existia o passo formularioenvolvidoAction()
            	$processo = new Processo($this->getTicket());
                $processo->abrirProcesso($postData);
                $session = new Zend_Session_Namespace('aberturaProcesso');
                $session->processo = $processo;
                $session->destino = $postData['destino']; // TODO
                $this->setSuccessMessage("Processo criado com sucesso");
                $this->_redirectUploadArquivo();*/
            }
            catch (AlfrescoApiException $e) {
            	throw $e;
            }
            catch (Exception $e) {
                throw $e;
            }
        }
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposProcesso = $listaTiposProcesso;
        $this->view->listaAssuntos = $listaAssuntos;
        $this->view->listaBairros = $listaBairros;
        $this->view->listaTiposManifestante = $listaTiposManifestante;
        $this->view->listaPrioridades = $listaPrioridades;
        $this->view->listaOrigens = $listaOrigens;
        $this->view->listaProtocolos = $listaProtocolos;
        $this->view->listaProprietarios = $listaProprietarios;
    }
    
    public function formularioenvolvidoAction()
    {
    	if ($this->getRequest()->isPost()) {
    		$session = new Zend_Session_Namespace('aberturaProcesso');
    		$formDadosGeraisProcesso = $session->formDadosGeraisProcesso;
    		      print '<pre>';
    		      print '$formDadosGeraisProcesso'."\n";
    		      var_dump($formDadosGeraisProcesso);
    		$postData = $this->getRequest()->getPost();
    		      print '$postData'."\n";
    		      var_dump($postData);
    		$dataMerged = array_merge($formDadosGeraisProcesso, $postData);
    		      print '$dataMerged'."\n";
    		      var_dump($dataMerged); print '</pre>';
    		$processo = new Processo($this->getTicket());
    		$processo->abrirProcesso($dataMerged);
    		$session->processo = $processo;
    		$this->_redirectUploadArquivo();
    	}
    }
    
    public function uploadarquivoAction()
    {
        $session = new Zend_Session_Namespace('aberturaProcesso');
        $this->view->processoUuid = $session->processo->id;
        $this->view->ticket = $this->getTicket();

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            $this->_redirectConfirmacaoCriacao();
        }
    }
    
    public function confirmacaocriacaoAction()
    {
        if ($this->getRequest()->isPost()) {
        	$session = new Zend_Session_Namespace('aberturaProcesso');
        	$processo = $session->processo;
        	
        	$postData['processoId'] = $processo->id;
        	$postData['destinoId'] = $processo->destino;
        	$postData['prioridadeId'] = $processo->prioridade->id;
        	$postData['prazo'] = $processo->data;
        	$postData['despacho'] = "";
        	
        	try {
        	    $processo->tramitar($postData);
        	} catch (AlfrescoApiException $e) {
        		throw $e;
        	} catch (Exception $e) {
        		throw $e;
        	}
        	
        	$this->_redirectProcessoDetalhes($processo->id);
        }
    }
    
    public function processocriadoAction()
    {
        $defaultNamespaceSession = new Zend_Session_Namespace('aberturaProcesso');
        $this->view->processo = $defaultNamespaceSession->processo;
    } 
    
    protected function _getIdTipoProcessoPost()
    {
        return ($this->getRequest()->getParam('tipoprocesso')) ? $this->getRequest()->getParam('tipoprocesso') : null;
    }
    
    protected function _getListaTiposProcesso()
    {
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $tiposProcesso = $tipoProcesso->listar();
        $listaTiposProcesso = array();
        foreach ($tiposProcesso as $tipoProcesso) {
            $listaTiposProcesso[$tipoProcesso->id] = $tipoProcesso->nome;
        }
        
        return $listaTiposProcesso;
    }
    
    protected function _redirectProcessoDetalhes($uuid)
    {
    	$this->_helper->redirector('detalhes', 'processo', 'default', array('id' => $uuid));
    }
    
    protected function _getIdTipoProcessoUrl()
    {
        $idTipoProcesso = $this->getRequest()->getParam('tipoprocesso');
        return $idTipoProcesso;
    }
    
    protected function _getTipoProcesso($idTipoProcesso = null)
    {
        $tipoProcesso = new TipoProcesso($this->getTicket());
        if ($idTipoProcesso) {
            $tipoProcesso->carregarPeloId($idTipoProcesso);
        }
        
        return $tipoProcesso;
    }
    
    protected function _getListaAssuntos(TipoProcesso $tipoProcesso)
    {
        $assuntos = $tipoProcesso->getAssuntos();
        $listaAssuntos = array();
        foreach ($assuntos as $assunto) {
            $listaAssuntos[$assunto->id] = $assunto->nome;
        }
        
        if (count($listaAssuntos) == 0) {
            throw new Exception(
                'O tipo de processo selecionado não possui nenhum assunto. Por favor, escolha outro.'
            );
        }
        
        return $listaAssuntos;
    }
    
    protected function _getListaTiposManifestante($tipoProcesso)
    {
        $tiposManifestante = $tipoProcesso->getTiposManifestante();
        
        $listaTiposManifestante = array();
        foreach ($tiposManifestante as $tipoManifestante) {
            $listaTiposManifestante[$tipoManifestante->id] = $tipoManifestante->descricao;
        }
        
        if (count($listaTiposManifestante) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de manifestante cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaTiposManifestante;
    }
    
    protected function _getListaBairros()
    {
        $bairro = new Bairro($this->getTicket());
        $bairros = $bairro->listar();
        $listaBairros = array();
        foreach ($bairros as $bairro) {
            $listaBairros[$bairro->id] = $bairro->descricao;
        }
        
        if (count($listaBairros) == 0) {
            throw new Exception(
                'Não existe nenhum bairro cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaBairros;
    }
    
    protected function _getListaPrioridades()
    {
        $prioridade = new Prioridade($this->getTicket());
        $prioridades = $prioridade->listar();
        $listaPrioridades = array();
        foreach ($prioridades as $prioridade) {
            $listaPrioridades[$prioridade->id] = $prioridade->descricao;
        }
        
        if (count($listaPrioridades) == 0) {
            throw new Exception(
                'Não existe nenhuma prioridade de processo cadastrada no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaPrioridades;
    }
    
	protected function _getListaOrigens()
    {
        $protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listar();
        $listaProtocolos = array();
        foreach ($protocolos as $protocolo) {
            $listaProtocolos[$protocolo->id] = $protocolo->descricao;
        }
        
        if (count($listaProtocolos) == 0) {
            throw new Exception(
                'Você não pode enviar nenhum processo, pois não possui acesso à nenhum protocolo.'
            );
        }
        
        return $listaProtocolos;
    }
    
    protected function _getListaProtocolos()
    {
        $protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listarTodos();
        $listaProtocolos = array();
        foreach ($protocolos as $protocolo) {
            $listaProtocolos[$protocolo->id] = $protocolo->descricao;
        }
        
        if (count($listaProtocolos) == 0) {
            throw new Exception(
                'Não existe nenhum protocolo cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaProtocolos;
    }
    
    protected function _getListaProprietarios()
    {
    	return $this->_getListaProtocolos();
    }
    
    protected function _redirectEscolhaTipoProcesso()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }
    
    protected function _redirectProcessoCriado()
    {
        $this->_helper->redirector('processocriado', $this->getController(), 'default');
    }
    
    protected function _redirectUploadArquivo()
    {
    	$this->_helper->redirector('uploadarquivo', $this->getController(), 'default');
    }
    
    protected function _redirectConfirmacaoCriacao()
    {
    	$this->_helper->redirector('confirmacaocriacao', $this->getController(), 'default');
    }
    
    protected function _redirectFormularioEnvolvido()
    {
    	$this->_helper->redirector('formularioenvolvido', $this->getController(), 'default');
    }

}
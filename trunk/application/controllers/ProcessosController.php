<?php
class ProcessosController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->view->abaAtiva = $this->getAction();	
	}
	
    public function indexAction()
    {
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEntrada();
    }
    
    public function receberAction()
    {
    	try {
    		if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('processosParaReceber')) {
    			throw new Exception("Por favor, selecione pelo menos um processo para receber.");
    		}
    		$processo = new Processo($this->getTicket());
    		$processo->receberVarios($this->getRequest()->getPost());
    		$this->setSuccessMessage('Processos recebidos com sucesso.');
    	} catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEntrada();
    	}
    	$this->_redirectEmAnalise();
    }
    
	protected function _redirectEmAnalise()
    {
    	$this->_helper->redirector('analise', $this->getController(), 'default');
    }
    
	protected function _redirectEntrada()
    {
    	$this->_helper->redirector('index', $this->getController(), 'default');
    }
    
    public function analiseAction()
    {
    	if ($this->getRequest()->isPost()) {
        	$processosSelecionados = $this->getRequest()->getParam('processos');
        	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        	
        	$isEncaminhar = ($this->getRequest()->getParam('encaminhar', false) !== false) ? true : false;
        	$isArquivar = ($this->getRequest()->getParam('arquivar', false) !== false) ? true : false;
        	$isExterno = ($this->getRequest()->getParam('externo', false) !== false) ? true : false;
        	
        	if ($isEncaminhar) {
        		$session = new Zend_Session_Namespace('encaminhar');
        		$session->processos = $processosSelecionados;
        		$this->_redirectEncaminhar();
        	} elseif ($isArquivar) {
        		$this->_redirectArquivar();
        	} elseif ($isExterno) {
        		$session = new Zend_Session_Namespace('encaminharExternos');
        		$session->processos = $processosSelecionados;
        		$this->_redirectEncaminharExternos();
        	}
        }
    	
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
    }
    
    protected function _redirectEncaminhar()
    {
    	$this->_helper->redirector('encaminhar', $this->getController(), 'default');
    }
    
	protected function _redirectArquivar()
    {
    	$this->_helper->redirector('encaminhar', $this->getController(), 'default');
    }
    
	protected function _redirectEncaminharExternos()
    {
    	$this->_helper->redirector('encaminharExternos', $this->getController(), 'default');
    }
    
    public function encaminharAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->tramitarVarios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Processos tramitados com sucesso.');
	    		$this->_redirectEmAnalise();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	$listaProtocolos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('encaminhar');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    	$listaProtocolos = $this->_getListaProtocolos();
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEmAnalise();
    	}
    	
        $this->view->processos = $processos;
        $this->view->listaProtocolos = $listaProtocolos;
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
    
    protected function _getListaCarregadaProcessos($listaComIdsProcessos)
    {
    	$processos = array();
    	foreach ($listaComIdsProcessos as $processoId) {
        	$processo = new Processo($this->getTicket());
        	$processo->carregarPeloId($processoId);
        	$processos[] = $processo;
        }
        
        return $processos;
    }
    
    public function encaminharexternosAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->tramitarExternos($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Processos tramitados com sucesso.');
	    		$this->_redirectEmAnalise();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
    	$processos = array();
    	
	    try {
	    	$session = new Zend_Session_Namespace('encaminharExternos');
	    	$processosSelecionados = $session->processos;
	    	$processos = $this->_getListaCarregadaProcessos($processosSelecionados);
	    } catch (Exception $e) {
    		$this->setErrorMessage($e->getMessage());
    		$this->_redirectEmAnalise();
    	}
    	
        $this->view->processos = $processos;
    }
    
	public function externosAction()
    {
    	$processo = new Processo($this->getTicket());
    	
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processosSelecionados = $this->getRequest()->getParam('processos');
    			$session = new Zend_Session_Namespace('encaminhar');
        		$session->processos = $processosSelecionados;
        		$this->_redirectEncaminhar();
    		} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
        $this->view->lista = $processo->listarProcessosCaixaExternos();
    }
    
	public function saidaAction()
    {
    	if ($this->getRequest()->isPost()) {
    		try {
    			$processo = new Processo($this->getTicket());
	    		$processo->cancelarEnvios($this->getRequest()->getPost());
	    		$this->setSuccessMessage('Envios cancelados com sucesso.');
	    		$this->_redirectEntrada();
			} catch (Exception $e) {
	    		$this->setMessageForTheView($e->getMessage(), 'error');
	    	}
    	}
    	
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaSaida();
    }
    
    public function enviadosAction()
    {
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaEnviados();
    }
    
    // início incorporação
    public function incorporacaoprincipalAction()
    {
    	// TODO Verificar possibilidades de quebrar a incorporação indo do passo 2 ao 1 e depois confirmando etc.
    	if ($this->getRequest()->isPost()) {
        	$postData = $this->getRequest()->getPost();
        	$session = new Zend_Session_Namespace('incorporacaoSession');
        	$session->principal = $postData['processos'][0];
        	$this->_redirectIncorporacaoIncorporado();
        }
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
    }
    
    public function incorporacaoincorporadoAction()
    {
    	$session = new Zend_Session_Namespace('incorporacaoSession');
    	if ($this->getRequest()->isPost()) {
    		$postData = $this->getRequest();
    		$session->incorporado = $postData['processos'][0];
    		$this->_redirectIncorporacaoConfirmacao();
    	}
    	$processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
        $this->view->principal = $session->principal;
    }
    
    public function incorporacaoconfirmacaoAction()
    {
    	$session = new Zend_Session_Namespace('incorporacaoSession');
        if ($this->getRequest()->isPost()) {
            $principal = $session->principal;
            $incorporado = $session->incorporado;
            $processo = new Processo($this->getTicket());
            try {
            	$processo->incorporar($principal, $incorporado);
            }
            catch (AlfrescoApiException $e) {
            	throw $e;
            }
            catch (Exception $e) {
            	throw $e;
            }
            $this->_redirectIncorporacaoDetalhes();
        }
        
        $this->view->principal = $session->principal;
        $this->view->incorporado = $session->incorporado;
    }
    
    public function incorporacaodetalhesAction()
    {
    	$session = new Zend_Session_Namespace('incorporacaoSession');
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest();
        }
        $this->view->principal = $session->principal;
    }
    
    protected function _redirectIncorporacaoIncorporado()
    {
        $this->_helper->redirector('incorporacaoincorporado', $this->getController(), 'default');
    }
    
    protected function _redirectIncorporacaoConfirmacao()
    {
        $this->_helper->redirector('incorporacaoconfirmacao', $this->getController(), 'default');
    }
    
    protected function _redirectIncorporacaoDetalhes()
    {
        $this->_helper->redirector('incorporacaodetalhes', $this->getController(), 'default');
    }
}
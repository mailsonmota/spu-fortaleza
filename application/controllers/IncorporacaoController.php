<?php
class IncorporacaoController extends BaseController
{
    public function indexAction()
    {
        // TODO Verificar possibilidades de quebrar a incorporação indo do passo 2 ao 1 e depois confirmando etc.
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $session = new Zend_Session_Namespace('incorporacaoSession');
            $session->processoPrincipalId = $postData['processos'][0];
            $this->_redirectEscolherIncorporado();
        }
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
    }
    
    public function escolherincorporadoAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $session->processoIncorporadoId = $postData['processos'][0];
            $this->_redirectConfirmacao();
        }
        $processo = new Processo($this->getTicket());
        $processo->carregarPeloId($session->processoPrincipalId);
        
        $processoNumero = $processo->numero;
        $processoNome = str_replace("/", "_", $processoNumero);

        $listaProcessosAnalise = $processo->listarProcessosCaixaAnalise();

        // Remove da lista o processo (principal) escolhido no passo anterior 
        for ($i = 0; $i < count($listaProcessosAnalise); $i++) {
        	if ($listaProcessosAnalise[$i]->nome == $processoNome) {
        		unset($listaProcessosAnalise[$i]);
        	}
        }
        
        $this->view->processo = $processo;
        $this->view->lista = $listaProcessosAnalise;
    }
    
    public function confirmacaoAction()
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
        
        $processoPrincipal = new Processo($this->getTicket());
        $processoPrincipal->carregarPeloId($session->processoPrincipalId);
        
        $processoIncorporado = new Processo($this->getTicket());
        $processoIncorporado->carregarPeloId($session->processoIncorporadoId);
        
        $this->view->principal = $processoPrincipal;
        $this->view->incorporado = $processoIncorporado;
    }
    
    public function detalhesAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest();
        }
        $this->view->principal = $session->principal;
    }
    
    protected function _redirectEscolherIncorporado()
    {
        $this->_helper->redirector('escolherincorporado', $this->getController(), 'default');
    }
    
    protected function _redirectConfirmacao()
    {
        $this->_helper->redirector('confirmacao', $this->getController(), 'default');
    }
    
    protected function _redirectDetalhes()
    {
        $this->_helper->redirector('detalhes', $this->getController(), 'default');
    }
}
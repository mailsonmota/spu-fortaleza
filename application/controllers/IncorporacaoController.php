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
        
        $listaCaixaAnaliseFiltrada = $processo->listarProcessosCaixaAnaliseIncorporado();
        
        $this->view->processo = $processo;
        $this->view->lista = $listaCaixaAnaliseFiltrada;
    }
    
    public function confirmacaoAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        if ($this->getRequest()->isPost()) {
            $data['principal'] = $session->processoPrincipalId;
            $data['incorporado'] =  $session->processoIncorporadoId;
            $processo = new Processo($this->getTicket());
            try {
                $processo->incorporar($data);
            }
            catch (AlfrescoApiException $e) {
                throw $e;
            }
            catch (Exception $e) {
                throw $e;
            }
            $this->_redirectConclusao();
        }
        
        $processoPrincipal = new Processo($this->getTicket());
        $processoPrincipal->carregarPeloId($session->processoPrincipalId);
        
        $processoIncorporado = new Processo($this->getTicket());
        $processoIncorporado->carregarPeloId($session->processoIncorporadoId);
        
        $this->view->principal = $processoPrincipal;
        $this->view->incorporado = $processoIncorporado;
    }
    
    public function conclusaoAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        
        $processoPrincipal = new Processo($this->getTicket());
        $processoPrincipal->carregarPeloId($session->processoPrincipalId);
        
        $processoIncorporado = new Processo($this->getTicket());
        $processoIncorporado->carregarPeloId($session->processoIncorporadoId);
        
        $this->view->principal = $processoPrincipal;
        $this->view->incorporado = $processoIncorporado;
    }
    
    protected function _redirectEscolherIncorporado()
    {
        $this->_helper->redirector('escolherincorporado', $this->getController(), 'default');
    }
    
    protected function _redirectConfirmacao()
    {
        $this->_helper->redirector('confirmacao', $this->getController(), 'default');
    }
    
    protected function _redirectConclusao()
    {
        $this->_helper->redirector('conclusao', $this->getController(), 'default');
    }
}
<?php
class IncorporacaoController extends BaseController
{
    public function indexAction()
    {
        // TODO Verificar possibilidades de quebrar a incorporação indo do passo 2 ao 1 e depois confirmando etc.
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $session = new Zend_Session_Namespace('incorporacaoSession');
            $session->principal = $postData['processos'][0];
            $this->_redirectEscolherIncorporado();
        }
        $processo = new Processo($this->getTicket());
        $this->view->lista = $processo->listarProcessosCaixaAnalise();
    }
    
    public function escolherincorporadoAction()
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
        
        $this->view->principal = $session->principal;
        $this->view->incorporado = $session->incorporado;
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
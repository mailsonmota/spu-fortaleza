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
        $processoService = new ProcessoService($this->getTicket());
        $this->view->lista = $processoService->getCaixaAnalise(0, 10000, null);
    }
    
    public function escolherincorporadoAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $session->processoIncorporadoId = $postData['processos'][0];
            $this->_redirectConfirmacao();
        }
        $processoService = new ProcessoService($this->getTicket());
        $processo = $processoService->getProcesso($session->processoPrincipalId);
        
        $caixaAnaliseIncorporacao = $processoService->getCaixaAnaliseIncorporacao($processo);
        
        for ($i = 0; $i < count($caixaAnaliseIncorporacao); $i++) {
            if ($caixaAnaliseIncorporacao[$i]->id == $processo->id) {
                unset($caixaAnaliseIncorporacao[$i]);
                array_values($caixaAnaliseIncorporacao);
            }
        }
        
        $this->view->processo = $processo;
        $this->view->lista = $caixaAnaliseIncorporacao;
    }
    
    public function confirmacaoAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        $processoService = new ProcessoService($this->getTicket());
        
        if ($this->getRequest()->isPost()) {
            $data['principal'] = $session->processoPrincipalId;
            $data['incorporado'] =  $session->processoIncorporadoId;
            
            try {
                $processoServico->incorporar($data);
            }
            catch (AlfrescoApiException $e) {
                throw $e;
            }
            catch (Exception $e) {
                throw $e;
            }
            $this->_redirectConclusao();
        }
        
        $processoPrincipal = new Processo();
        $processoPrincipal = $processoService->getProcesso($session->processoPrincipalId);
        
        $processoIncorporado = new Processo();
        $processoIncorporado = $processoService->getProcesso($session->processoIncorporadoId);
        
        $this->view->principal = $processoPrincipal;
        $this->view->incorporado = $processoIncorporado;
    }
    
    public function conclusaoAction()
    {
        $session = new Zend_Session_Namespace('incorporacaoSession');
        $processoService = new ProcessoService($this->getTicket());
        
        $processoPrincipal = new Processo();
        $processoPrincipal = $processoService->getProcesso($session->processoPrincipalId);
        
        $processoIncorporado = new Processo();
        $processoIncorporado = $processoService->getProcesso($session->processoIncorporadoId);
        
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
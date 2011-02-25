<?php
require_once('BaseTramitacaoController.php');
class ExternosController extends BaseTramitacaoController
{
    public function indexAction()
    {
        $tramitacaoService = new TramitacaoService($this->getTicket());
        
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
        
        $this->view->lista = $tramitacaoService->getCaixaExternos();
    }
}
<?php

class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector(null, null, null, array('q' => trim(str_replace("/", "%2F", $_POST['q']))));
        }

        if ($this->_getParam('q')) {
            $service = new Spu_Service_Manifestante($this->_getTicketAdmin());
            $this->view->paginator = $this->_helper->paginator()->paginate(
                    $service->getManifestantes(
                            $this->_helper->paginator()->getOffset(), $this->_helper->paginator()->getPageSize(), str_replace("%2F", "/", $this->_getParam('q'))
                    )
            );
        } else {
            $this->setMessageForTheView('Por favor, busque pelo Nome, CPF ou CNPJ.');
        }

        $this->view->q = str_replace("%2F", "/", $this->_getParam('q'));
    }

    public function detalhesAction()
    {
        $manifestanteService = new Spu_Service_Manifestante($this->getTicket());
        $manifestante = $manifestanteService->getManifestante($this->_getCpfFromUrl());

        $this->view->manifestante = $manifestante;
        $this->view->id = $manifestante->getCpf();
        $this->view->isEdit = true;
    }

    private function _getCpfFromUrl()
    {
        $cpf = $this->getRequest()->getParam('cpf');
        return $cpf;
    }

    private function _getBaseUrl()
    {
        $init = new Zend_Config_Ini('../application/configs/application.ini', APPLICATION_ENV);
        return $init->alfresco->url;
    }

    private function _getTicketAdmin()
    {
        $alfresco = new Alfresco_Rest_Login($this->_getBaseUrl() . "/service");
        $ticket = $alfresco->login("login", "senha");
        $alfresco->logout($ticket['ticket']);
        
        return $ticket['ticket'];
    }
}
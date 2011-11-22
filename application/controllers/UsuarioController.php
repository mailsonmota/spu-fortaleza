<?php

class UsuarioController extends BaseController
{

    public function cadastroAction()
    {
        $this->view->nomeProtocolos = $this->_getNomeProtocolosUsuario();
    }

    public function editAction()
    {
        $data = $this->_request->getParam('q');

        if ($data) {
            if (!$data['new'] || !$data['confirm'] || !$data['current']) {
                $this->redirectFalha('Operação inválida! Preencha todos os campos.');
            }
            else if ($data['new'] != $data['confirm']) {
                $this->redirectFalha('Falha na operação! A senha e sua confirmação não estão iguais.');
            }
            else if (strlen($data['new']) < 3){
                $this->redirectFalha('Atenção, a nova senha deve conter no mínimo 3 caracteres.');
            }
            else {
                $this->setMessageForTheView(print_r($data));
            }
        }
    }

    private function redirectFalha($str = 'Ops!!! Ocorreu um erro.')
    {   $this->setErrorMessage($str);
        $this->_helper->redirector('edit');
    }

    protected function _getNomeProtocolosUsuario()
    {
        if ($this->view->pessoa->isAdministrador()) {
            return array('Administrador');
        }

        $service = new Spu_Service_Protocolo($this->getTicket());
        $nomeProtocolos = array();
        foreach ($service->getProtocolos() as $p) {
            $nomeProtocolos[] = $p->path;
        }

        return $nomeProtocolos;
    }

}
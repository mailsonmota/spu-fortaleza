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
                $this->_redirectFalha('Operação inválida! Preencha todos os campos.');
            } else if ($data['new'] != $data['confirm']) {
                $this->_redirectFalha('Falha na operação! A senha e sua confirmação não estão iguais.');
            } else if (strlen($data['new']) < 3) {
                $this->_redirectFalha('Atenção, a nova senha deve conter no mínimo 3 caracteres.');
            } else {
                if (!$this->_checkSenhaAtual($data['current'])) {
                    $this->_redirectFalha('Atenção, a senha atual do usuário não está correta.');
                } else {
                    if ($this->_updateSenha($data['current'], $data['new'])) {
                        $this->_redirectSucess('Operação realizada com sucesso!');
                    }
                    else {
                        $this->_redirectFalha();
                    }
                }
            }
        }
    }

    private function _redirectFalha($str = 'Ops!!! Ocorreu um erro.')
    {
        $this->setErrorMessage($str);
        $this->_helper->redirector('edit');
    }

    private function _redirectSucess($str = 'Ops!!! Ocorreu um erro.')
    {
        $this->setSuccessMessage($str);
        $this->_helper->redirector('edit');
    }

    private function _updateSenha($oldpw, $newpw)
    {
        $alfrescoPerson = new Alfresco_Rest_Person(Spu_Service_Abstract::getAlfrescoUrl(), $this->getTicket());

        return $alfrescoPerson->updatePassword($this->getUser()->login, $oldpw, $newpw);
    }

    private function _checkSenhaAtual($password)
    {
        $alfrescoLogin = new Alfresco_Rest_Login(Spu_Service_Abstract::getAlfrescoUrl(), $this->getTicket());

        return $alfrescoLogin->checkLogin($this->getUser()->login, $password);
    }

    protected function _getNomeProtocolosUsuario()
    {
        if ($this->view->pessoa->isAdministrador()) {
            return array('Administrador');
        }

        $service = new Spu_Service_Protocolo($this->getTicketSearch());
        $nomeProtocolos = array();
        foreach ($service->getProtocolos() as $p) {
            $nomeProtocolos[] = $p->path;
        }

        return $nomeProtocolos;
    }

}
<?php
Loader::loadAlfrescoObject('User');
Loader::loadAlfrescoObject('Orgao');
class Usuario extends User
{
    public function getOrgao()
    {
        try {
            $orgao = new Orgao($this->getTicket());
            $grupos = $orgao->getGroups();
            $homeFolder = $this->getHomeFolder();
            echo '<pre>';
            var_dump($this->getTicket());
            var_dump($this->getDetails());
            echo '</pre>';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
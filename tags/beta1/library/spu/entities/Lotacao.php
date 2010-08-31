<?php
Loader::loadModel('LotacaoDao.php');
class Lotacao extends BaseCrudEntity
{
    protected $_dao = 'LotacaoDao';
    
    public function getPai()
    {
    }
    
    public function getPossiveisPais()
    {
        $criterio = null;
        if ($this->getTableKeyValue()) {
            $criterio = $this->getTableKey() . ' <> ' . $this->getTableKeyValue();
        }

        return $this->listar($criterio);
    }
}
?>
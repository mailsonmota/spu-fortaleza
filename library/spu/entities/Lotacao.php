<?php
Loader::loadModel('LotacaoDao.php');
class Lotacao extends BaseCrudEntity
{
    protected $_dao = 'LotacaoDao';
    
    protected function setDefaultValues()
    {
        $this->setField('ativa', true);
        $this->setField('orgao', false);
    }
    
    public function getPai()
    {
        return new Lotacao(null, $this->getField('pai'));
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
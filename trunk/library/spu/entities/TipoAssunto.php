<?php
Loader::loadModel('AssuntoDao.php');
class TipoAssunto extends BaseCrudEntity
{
    protected $_dao = 'AssuntoDao';
    
    protected function setDefaultValues()
    {
        $this->setField('notificar', false);
    }
    
    public function getFormularios()
    {
        return $this->getDao()->fetchAll();
    }
}
?>
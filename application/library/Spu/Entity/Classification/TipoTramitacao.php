<?php
class Spu_Entity_Classification_TipoTramitacao extends Spu_Entity_Classification_Abstract
{
    const PARALELA = 'Paralelo';
    
    public function isParalela()
    {
        return ($this->_nome == self::PARALELA);
    }
}
<?php
require_once('BaseClassification.php');
class TipoTramitacao extends BaseClassification
{
	const PARALELA = 'Paralelo';
    
    public function isParalela()
    {
    	return ($this->_nome == self::PARALELA);
    }
}
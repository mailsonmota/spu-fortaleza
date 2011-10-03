<?php
/**
 * Representa um Tipo de Tramitação de Tipo de Processo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Classification_Abstract
 */
class Spu_Entity_Classification_TipoTramitacao extends Spu_Entity_Classification_Abstract
{
    const PARALELA = 'Paralelo';
    
    /**
     * Retorna se o tipo de tramitação é paralelo
     * 
     * @return boolean
     */
    public function isParalela()
    {
        return ($this->_nome == self::PARALELA);
    }
}
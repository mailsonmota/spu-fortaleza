<?php
require_once('Formulario.php');
class Assunto extends BaseEntity
{
    protected $_dao = 'AssuntoDao';
    
    public function getFormularios() {
        $formularios = array();
        
        $formularios[] = new Formulario();
        
        return $formularios;
    }
}
?>
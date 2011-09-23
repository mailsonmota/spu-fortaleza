<?php
/**
 * Representa um assunto do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Abstract
 */
class Spu_Entity_Assunto extends Spu_Entity_Abstract
{
    protected $_nodeRef;
    protected $_nome;
    protected $_corpo;
    protected $_notificarNaAbertura;
    protected $_tipoProcesso;
    protected $_formulario;
    
    /**
     * Retorna o Tipo de Processo à qual o assunto pertence
     * 
     * @return Spu_Entity_TipoProcesso
     */
    public function getTipoProcesso()
    {
        return $this->_tipoProcesso;
    }
    
    public function setTipoProcesso($value)
    {
        $this->_tipoProcesso = $value;
    }

    public function getNodeRef()
    {
        return $this->_nodeRef;
    }

    public function setNodeRef($nodeRef)
    {
        $this->_nodeRef = $nodeRef;
    }

    public function getNome()
    {
        return $this->_nome;
    }

    public function setNome($nome)
    {
        $this->_nome = $nome;
    }

    public function getCorpo()
    {
        return $this->_corpo;
    }

    public function setCorpo($value)
    {
        $this->_corpo = $value;
    }

    /**
     * @return boolean
     */
    public function getNotificarNaAbertura()
    {
        return $this->_notificarNaAbertura;
    }

    public function setNotificarNaAbertura($value)
    {
        $this->_notificarNaAbertura = $value;
    }

    /**
     * Retorna o formulário do assunto
     * 
     * @return Spu_Entity_Formulario
     */
    public function getFormulario() {
        return $this->_formulario;
    }
    
    public function setFormulario($value)
    {
        $this->_formulario = $value;
    }
    
    public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    /**
     * @return boolean
     */
    public function hasFormulario()
    {
        if ($this->_formulario) {
            return $this->_formulario->hasData();
        }

        return false;
    }
}
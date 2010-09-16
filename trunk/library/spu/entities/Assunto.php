<?php
require_once('../library/Alfresco/API/AlfrescoAssuntos.php');
require_once('BaseAlfrescoEntity.php');
class Assunto extends BaseAlfrescoEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_categoria;
    
    public function listar()
    {
        $service = new AlfrescoAssuntos(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeAssuntos = $service->getAssuntos();
        
        $assuntos = array();
        foreach ($hashDeAssuntos as $hash) {
            echo '<pre>';
            var_dump($hash);
            echo '</pre>';
            exit;
            $assunto = new Assunto($this->_getTicket());
            $assunto->loadAssuntoFromHash($hash);
            $assuntos[] = $assunto;
        }
        
        return $assuntos;
    }
    
    public function loadAssuntoFromHash($hash)
    {
        $this->setNodeRef($hash['noderef']);
        $this->setNome($hash['nome']);
        $this->setCategoria($hash['categoria']);
    }
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }
    
    public function getCategoria()
    {
        return $this->_categoria;
    }
    
    public function setCategoria($categoria)
    {
        $this->_categoria = $categoria;
    }
    
    public function getNodeRef()
    {
        return $this->_nodeRef;
    }
    
    public function setNodeRef($nodeRef)
    {
        $this->_nodeRef = $nodeRef;
    }
}
?>
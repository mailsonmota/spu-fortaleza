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
        foreach ($hashDeAssuntos as $categorias) {
            foreach ($categorias as $categoria => $assuntosCategoria) {
                foreach ($assuntosCategoria as $hashAssunto) {
                    $assunto = new Assunto($this->_getTicket());
                    $assunto->loadAssuntoFromHash($hashAssunto);
                    $assunto->setCategoria($categoria);
                    $assuntos[] = $assunto;
                }
            }
        }
        
        return $assuntos;
    }
    
    public function loadAssuntoFromHash($hash)
    {
        $this->setNodeRef($hash['noderef']);
        $this->setNome($hash['nome']);
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
    
    public function listarPorTipoProcesso($nomeTipoProcesso)
    {
        $service = new AlfrescoAssuntos(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeAssuntos = $service->getAssuntosPorTipoProcesso($nomeTipoProcesso);
        
        $assuntos = array();
        foreach ($hashDeAssuntos as $categorias) {
            foreach ($categorias as $categoria => $assuntosCategoria) {
                foreach ($assuntosCategoria as $hashAssunto) {
                    $assunto = new Assunto($this->_getTicket());
                    $assunto->loadAssuntoFromHash($hashAssunto);
                    $assunto->setCategoria($categoria);
                    $assuntos[] = $assunto;
                }
            }
        }
        
        return $assuntos;
    }
}
?>
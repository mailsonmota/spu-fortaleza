<?php
require_once('BaseEntity.php');
Loader::loadDao('AssuntoDao');
Loader::loadDao('ArquivoDao');
class Assunto extends BaseEntity
{
	protected $_nodeRef;
	protected $_nome;
	protected $_categoria;
	protected $_corpo;
	protected $_notificarNaAbertura;
	protected $_tipoProcesso;
	
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

	public function getCategoria()
	{
		return $this->_categoria;
	}

	public function setCategoria($categoria)
	{
		$this->_categoria = $categoria;
	}

	public function getCorpo()
	{
		return $this->_corpo;
	}

	public function setCorpo($value)
	{
		$this->_corpo = $value;
	}

	public function getNotificarNaAbertura()
	{
		return $this->_notificarNaAbertura;
	}

	public function setNotificarNaAbertura($value)
	{
		$this->_notificarNaAbertura = $value;
	}

	public function getId()
	{
		$nodeRef = $this->getNodeRef();
		return substr($nodeRef, strrpos($nodeRef, '/') + 1);
	}

	public function listar()
	{
		$dao = $this->_getDao();
		$hashDeAssuntos = $dao->getAssuntos();

		$assuntos = array();
		foreach ($hashDeAssuntos[0] as $hashAssunto) {
			$assunto = new Assunto($this->_getTicket());
			$assunto->_loadAssuntoFromHash($hashAssunto[0]);
			$assunto->setCategoria($hashAssunto[0]['tipoProcesso']);
			$assuntos[] = $assunto;
		}

		return $assuntos;
	}

	protected function _getDao()
	{
		$dao = new AssuntoDao($this->_getTicket());
		return $dao;
	}
	
	protected function _getArquivoDao()
	{
		$dao = new ArquivoDao($this->_getTicket());
		return $dao;
	}

	protected function _loadAssuntoFromHash($hash)
	{
		$this->setNodeRef($this->_getHashValue($hash, 'noderef'));
		$this->setNome($this->_getHashValue($hash, 'nome'));
		$this->setCorpo($this->_getHashValue($hash, 'corpo'));
		$this->setNotificarNaAbertura($this->_getHashValue($hash, 'notificarNaAbertura') ? true : false);
		$this->setTipoProcesso($this->_loadTipoProcessoFromHash($this->_getHashValue($hash, 'tipoProcesso')));
	}
	
	protected function _loadTipoProcessoFromHash($hash){
		$tipoProcesso = new TipoProcesso($this->_ticket);
		if ($hash) {
			$hash = array_pop($hash);
			$tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
		}		
        return $tipoProcesso;
	}

	public function listarPorTipoProcesso($idTipoProcesso)
	{
		$dao = $this->_getDao();
		$hashDeAssuntos = $dao->getAssuntosPorTipoProcesso($idTipoProcesso);

		$assuntos = array();
		foreach ($hashDeAssuntos as $hashAssunto) {
			$assunto = new Assunto($this->_getTicket());
			$assunto->_loadAssuntoFromHash($hashAssunto);
			$assuntos[] = $assunto;
		}

		return $assuntos;
	}

	public function carregarPeloId($id)
	{
		$dao = $this->_getDao();
		$hashDeAssuntos = $dao->getAssunto($id);

		foreach ($hashDeAssuntos as $hashAssunto) {
			$hashDadosAssunto = array_pop($hashAssunto);
			$this->_loadAssuntoFromHash($hashDadosAssunto);
			$this->setCategoria($hashDadosAssunto['tipoProcesso']);
		}
	}
	
	public function inserir($postData)
    {
    	$dao = $this->_getDao();
    	$hashDeAssuntos = $dao->inserir($postData);

        foreach ($hashDeAssuntos as $hashAssunto) {
            $hashDadosAssunto = array_pop($hashAssunto); 
            $this->_loadAssuntoFromHash($hashDadosAssunto);
            $this->setCategoria($hashDadosAssunto['tipoProcesso']);
        }
    }
    
    public function editar($postData)
    {
    	$dao = $this->_getDao();
        $hashDeAssuntos = $dao->editar($this->getId(), $postData);

        foreach ($hashDeAssuntos as $hashAssunto) {
            $hashDadosAssunto = array_pop($hashAssunto); 
            $this->_loadAssuntoFromHash($hashDadosAssunto);
            $this->setCategoria($hashDadosAssunto['tipoProcesso']);
        }
    }
	
	//FIXME: Implementar Assunto::hasFormulario
	public function hasFormulario()
	{
		try {
		    $formularioXsd = $this->getFormularioXsd();
		    return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function getFormularioXsd()
	{
		$dao = $this->_getArquivoDao();
		$params = array("id" => $this->getId());
		return $dao->getContentFromUrl($params);
	}
}
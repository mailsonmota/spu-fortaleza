<?php
require_once('../library/Alfresco/API/AlfrescoProtocolo.php');
require_once('BaseEntity.php');
class Protocolo extends BaseEntity
{
	protected $_nodeRef;
	protected $_nome;
	protected $_descricao;
	protected $_orgao;
	protected $_lotacao;
	protected $_recebePelosSubsetores;
	protected $_recebeMalotes;

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

	public function getDescricao()
	{
		return $this->_descricao;
	}

	public function setDescricao($data)
	{
		$this->_descricao = $data;
	}

	public function getOrgao()
	{
		return $this->_orgao;
	}

	public function setOrgao($data)
	{
		$this->_orgao = $data;
	}

	public function getLotacao()
	{
		return $this->_lotacao;
	}

	public function setLotacao($data)
	{
		$this->_lotacao = $data;
	}

	public function getRecebePelosSubsetores()
	{
		return $this->_recebePelosSubsetores;
	}
	
	public function setRecebePelosSubsetores($value)
	{
		$this->_recebePelosSubsetores = $value;
	}
	
	public function getRecebeMalotes()
	{
		return $this->_recebeMalotes;
	}
	
	public function setRecebeMalotes($value)
	{
		$this->_recebeMalotes = $value;
	}

	public function getId()
	{
		$nodeRef = $this->getNodeRef();
		return substr($nodeRef, strrpos($nodeRef, '/') + 1);
	}

	public function getOrgaoLotacao()
	{
		if ($this->getLotacao()) {
			$orgaoLotacao = $this->getLotacao();
			if ($this->getOrgao()) {
				$orgaoLotacao = $this->getOrgao() . " - " . $orgaoLotacao;
			}
		} else {
			$orgaoLotacao = ($this->getDescricao()) ? $this->getDescricao() : $this->getNome();
		}
		return $orgaoLotacao;
	}

	public function listar()
	{
		$service = new AlfrescoProtocolo(self::ALFRESCO_URL, $this->_getTicket());
		$hashProtocolos = $service->getProtocolos();

		return $this->loadManyFromHash($hashProtocolos);
	}

	public function loadManyFromHash($hashProtocolos)
	{
		$protocolos = array();
		foreach ($hashProtocolos as $hashProtocolo) {
			$hashProtocolo = array_pop($hashProtocolo);
			$protocolo = new Protocolo($this->_getTicket());
			$protocolo->loadFromHash($hashProtocolo);
			$protocolos[] = $protocolo;
		}

		return $protocolos;
	}

	public function loadFromHash($hash)
	{
		$this->setNodeRef($hash['noderef']);
		$this->setNome($hash['nome']);
		$this->setDescricao($hash['descricao']);
		$this->setRecebePelosSubsetores(($this->_getHashValue($hash, 'recebePelosSubsetores') == '1') ? true : false);
		$this->setRecebeMalotes(($this->_getHashValue($hash, 'recebeMalotes') == '1') ? true : false);
	}

	public function listarTodos()
	{
		$service = new AlfrescoProtocolo(self::ALFRESCO_URL, $this->_getTicket());
		$hashProtocolos = $service->getTodosProtocolos();

		return $this->loadManyFromHash($hashProtocolos);
	}

	public function carregarPeloId($id)
	{
		$service = new AlfrescoProtocolo(self::ALFRESCO_URL, $this->_getTicket());
		$hashDeProtocolo = $service->getProtocolo($id);

		foreach ($hashDeProtocolo as $hashProtocolo) {
			$hashDadosProtocolo = array_pop($hashProtocolo);
			$this->loadFromHash($hashDadosProtocolo);
		}
	}
}
?>
<?php
class Spu_Service_Assunto extends Spu_Service_Abstract
{
    private $_assuntosBaseUrl = 'spu/assuntos';
    private $_assuntosTicketUrl = 'ticket';

    public function getAssuntos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['assuntos']);
    }

    public function getAssuntosPorTipoProcesso($idTipoProcesso, $origem = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listarportipoprocesso/$idTipoProcesso";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['assuntos']);
    }

    public function getAssunto($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])), true);
    }

    public function inserir($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/inserir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])), true);
    }

    public function inserirFormularioModelo(array $dados)
    {
        try {
            $xsd = XSDCreator::create($dados);

            $fileName = self::_getXsdFileName($xsd);
            $filePath = sys_get_temp_dir() . '/' . $fileName;

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $fh = fopen($filePath, 'w');
            fwrite($fh, $xsd);
            fclose($fh);

            $arquivoService = new Spu_Service_Arquivo($this->getTicket());
            $arquivoService->uploadArquivo(array('destNodeUuid' => $dados['id'], 'fileToUpload' => '@' . $filePath));
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function editar($id, $postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/editar/$id";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])), true);
    }

    public function loadFromHash($hash, $carregarDetalhes = false)
    {
        $assunto = new Spu_Entity_Assunto();

        $assunto->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $assunto->setNome($this->_getHashValue($hash, 'nome'));
        $assunto->setCorpo($this->_getHashValue($hash, 'corpo'));
        $assunto->setNotificarNaAbertura($this->_getHashValue($hash, 'notificarNaAbertura') ? true : false);
        if ($carregarDetalhes) {
            $assunto->setTipoProcesso($this->_loadTipoProcessoFromHash($this->_getHashValue($hash, 'tipoProcesso')));
            $assunto->setFormulario($this->_loadFormulario($assunto->getId()));
        }

        return $assunto;
    }

    protected function _loadTipoProcessoFromHash($hash){
        $tipoProcesso = new Spu_Entity_TipoProcesso();
        if ($hash AND is_array($hash)) {
            $hash = array_pop($hash);
            $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
            $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));
        }
        return $tipoProcesso;
    }

    protected function _loadFormulario($assuntoId)
    {
        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $formulario = new Spu_Entity_Formulario();
        try {
            $formularioData = $arquivoService->getContentFromUrl(array('id' => $assuntoId));
            $formulario->setData($formularioData);
        } catch (Exception $e) {

        }

        return $formulario;
    }

    protected function _loadManyFromHash($hash)
    {
        $hashAssuntos = array();
        foreach ($hash as $hashAssunto) {
            //FIXME: Esse service nao esta respondendo coforme o padrão
            //$hashAssunto = array_pop($hashAssunto);
            $hashAssuntos[] = $this->loadFromHash($hashAssunto);
        }

        return $hashAssuntos;
    }

    public function removerVarios($hash)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/remover";
        $result = $this->_doAuthenticatedPostRequest($url, $hash);

        return true;
    }

    protected static function _getXsdFileName($xsd)
    {
        $simpleXmlObject = simplexml_load_string($xsd);

        preg_match("/^.+:(.+)/", $simpleXmlObject['targetNamespace'], $pregMatchResult);

        return $pregMatchResult[1] . '.xsd';
    }
}
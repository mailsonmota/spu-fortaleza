<?php
/**
 * Classe para acessar os serviços de Assunto do SPU
 *
 * @author Prefeitura de Fortaleza <fortaleza.ce.gov.br>
 * @author Gil Magno <gilmagno@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Assunto extends Spu_Service_Abstract
{
    /**
     * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
     * @var string
     */
    private $_assuntosBaseUrl = 'spu/assuntos';

    /**
     * Lista todos os assuntos
     *
     * @return array de objetos Spu_Entity_Assunto
     */
    public function getAssuntos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listar";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->_loadManyFromHash($result['assuntos']);
    }

    /**
     * Lista todos os assuntos de um tipo de processo
     *
     * @var string $idTipoProcesso Id do tipo de processo
     * @var string $origem Id do protocolo de origem
     * @return array de Spu_Entity_Assunto
     */
    public function getAssuntosPorTipoProcesso($idTipoProcesso)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listarportipoprocesso/$idTipoProcesso";
        
        $name = $this->getNameForMethod('getAssuntosPorTipoProcesso', $idTipoProcesso);
        if (($result = $this->getCache()->load($name)) === false) {

            $result = $this->_doAuthenticatedGetRequest($url);

            $this->getCache()->save($result, $name);
        }
        

        return $this->_loadManyFromHash($result['assuntos']);
    }

    /**
     * Retorna o assunto de um determinado id
     *
     * @param string $nodeUuid Id do assunto
     * @return Spu_Entity_Protocolo
     */
    public function getAssunto($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])), true);
    }

    /**
     * Insere um modelo de formulário, que é um arquivo .xsd, em um assunto
     *
     * @var array $dados Dados que serão usados para gerar o arquivo .xsd
     * @return void
     */
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

    /**
     * Carrega um assunto a partir de um hash. Esse hash é um array feito a partir
     * do json retornado pelo Alfresco, usando a função json_decode().
     *
     * @param array $hash
     * @param boolean $carregarDetalhes
     * @return Spu_Entity_Assunto
     */
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

    /**
     * Insere assunto em tipo de processo
     *
     * @var array $postData Dados vindos do formulário de inserção de assunto
     * @return Spu_Entity_Assunto
     */
    public function inserir($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/inserir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])), true);
    }

    /**
     * Edita um assunto de um tipo de processo
     *
     * @var string $id Id do assunto
     * @var array $postData Dados vindos do formulário de edição de assunto
     * @return Spu_Entity_Assunto
     */
    public function editar($id, $postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/editar/$id";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])), true);
    }

    /**
     * TODO FIXME Testar/corrigir essa funcionalidade
     *
     * Remove vários assuntos
     *
     * @var hash $hash
     * @return boolean
     */
    public function removerVarios($hash)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/remover";
        $result = $this->_doAuthenticatedPostRequest($url, $hash);

        return true;
    }

    /**
     * Carrega um tipo de processo a partir de um hash. Esse hash é um assunto,
     * retornado pelo Alfresco
     *
     * @var array $hash Informações sobre um assunto
     * @return Spu_Entity_TipoProcesso
     */
    protected function _loadTipoProcessoFromHash($hash) {
        $tipoProcesso = new Spu_Entity_TipoProcesso();
        if ($hash AND is_array($hash)) {
            $hash = array_pop($hash);
            $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
            $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));
        }
        return $tipoProcesso;
    }

    /**
     * Carrega o formulário de um assunto
     *
     * @var string $assuntoId
     * @return string $formulario
     */
    protected function _loadFormulario($assuntoId)
    {
        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $formulario = new Spu_Entity_Formulario();
        try {
            $url = $arquivoService->getArquivoFormularioDownloadUrl($assuntoId);
            $formularioData = $arquivoService->getContentFromUrl($url);
            
            if (strpos($formularioData, '<xs:schema') > -1) {
                $formulario->setData($formularioData);
            }
        } catch (Exception $e) {

        }

        return $formulario;
    }

    /**
     * Carrega vários assuntos a partir de um hash. Esse hash é um array feito a partir
     * do json retornado pelo Alfresco, usando a função json_decode().
     *
     * @param array $hash
     * @return array de Spu_Entity_Assunto
     */
    public function _loadManyFromHash($hash)
    {
        $hashAssuntos = array();
        foreach ($hash as $hashAssunto) {
            $hashAssuntos[] = $this->loadFromHash($hashAssunto);
        }

        return $hashAssuntos;
    }

    /**
     * Extrai um valor determinado do conteúdo XSD. Esse valor será
     * o nome do arquivo que conterá seu conteúdo.
     *
     * @var string $xsd Conteúdo do XSD
     * @return string
     */
    protected static function _getXsdFileName($xsd)
    {
        try {
            $simpleXmlObject = simplexml_load_string($xsd);
        } catch (Exception $e) {
            throw $e;
        }

        preg_match("/^.+:(.+)/", $simpleXmlObject['targetNamespace'], $pregMatchResult);

        return $pregMatchResult[1] . '.xsd';
    }
}

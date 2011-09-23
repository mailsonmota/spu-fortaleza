<?php
/**
 * Classe para acessar os serviços de tipo de processo do SPU
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_TipoProcesso extends Spu_Service_Abstract
{
        /**
         * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
         * @var string
         */
    private $_tiposProcessoBaseUrl = 'spu/tiposprocesso';

    /**
     * Retorna todos os tipos de processo
     *
     * @param string $origem id do protocolo de origem da solicitação
     * @return Spu_Entity_TipoProcesso[]
     */
    public function getTiposProcesso($origem = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/listar";

        if ($origem) {
                $url .= "/$origem";
        }

        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->_loadManyFromHash($result['Tipos de Processo'][0]);
    }

    /**
     * Retorna o tipo de processo pelo ID
     *
     * @param string $nodeUuid
     * @return Spu_Entity_TipoProcesso
     */
    public function getTipoProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->loadFromHash(array_pop($result['Tipo de Processo']));
    }

    /**
     * Carrega o tipo de processo através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_TipoProcesso
     */
    public function loadFromHash($hash)
    {
        $tipoProcesso = new Spu_Entity_TipoProcesso();

        $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));
        $tipoProcesso->setSimples(($this->_getHashValue($hash, 'simples') == '1') ? true : false);
        $tipoProcesso->setTitulo($this->_getHashValue($hash, 'titulo'));
        $tipoProcesso->setLetra($this->_getHashValue($hash, 'letra'));
        $tipoProcesso->setObservacao($this->_getHashValue($hash, 'observacao'));
        $tipoProcesso->setEnvolvidoSigiloso(($this->_getHashValue($hash, 'envolvidoSigiloso') == '1') ? true : false);
        $tipoProcesso->setTiposManifestante($this->_getHashValue($hash, 'tiposManifestante'));
        $tipoProcesso->setTramitacao($this->_loadTipoTramitacaoFromHash($this->_getHashValue($hash, 'tramitacao')));
        $tipoProcesso->setAbrangencia(
                $this->_loadTipoAbrangenciaFromHash($this->_getHashValue($hash, 'abrangencia'))
        );
        $tipoProcesso->setTiposManifestante(
                $this->_loadTiposManifestanteFromHash($this->_getHashValue($hash, 'tiposManifestante'))
        );

        return $tipoProcesso;
    }

    /**
     * Carrega o tipo de tramitação através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_Classification_TipoTramitacao
     */
    protected function _loadTipoTramitacaoFromHash($hash)
    {
        $service = new Spu_Service_TipoTramitacao();
        $tramitacao = $service->loadFromHash($hash);

        return $tramitacao;
    }

    /**
     * Carrega o tipo de abrangência através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_Classification_TipoAbrangencia
     */
    protected function _loadTipoAbrangenciaFromHash($hash)
    {
        $service = new Spu_Service_TipoAbrangencia();
        $abrangencia = $service->loadFromHash($hash);

        return $abrangencia;
    }

    /**
     * Carrega os tipos de manifestante através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_Classification_TipoManifestante[]
     */
    protected function _loadTiposManifestanteFromHash($hash)
    {
        $tiposManifestante = array();
        if ($hash) {
            $hash = array_pop($hash);
            foreach ($hash as $hashTipoManifestante) {
                $service = new Spu_Service_TipoManifestante();
                $tiposManifestante[] = $service->loadFromHash($hashTipoManifestante);
            }
        }
        return $tiposManifestante;
    }

    /**
     * Carrega vários tipos de processo através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_TipoProcesso
     */
    protected function _loadManyFromHash($hash)
    {
        $tiposProcesso = array();
                if ($hash) {
                foreach ($hash as $hashTipoProcesso) {
                    $hashTipoProcesso = array_pop($hashTipoProcesso);
                    $tiposProcesso[] = $this->loadFromHash($hashTipoProcesso);
                }
                }

        return $tiposProcesso;
    }
}

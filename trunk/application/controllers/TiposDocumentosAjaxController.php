<?php
class TiposDocumentosAjaxController extends BaseAuthenticatedController
{
    public function buscarAction()
    {
        $this->_helper->layout()->disableLayout();

        $term = $this->_getParam('term');

        $service = new Spu_Service_TipoDocumento($this->getTicket());

        $this->view->resultados = $service->buscar($term);
    }

    public function listarDestinosFilhosAction()
    {
        $service = new Spu_Service_Protocolo($this->getTicket());
        $protocolos = $service->getProtocolosFilhos($this->_getParam('parent-id'), $this->_getParam('origem'));

        $protocolosJson = array();
        foreach ($protocolos as $protocolo) {
            $name = substr($protocolo->path, strpos($protocolo->path, '/') + 1);
            $shortname = explode("/", $name);
            $shortname = array_pop($shortname) . " - " . $protocolo->descricao;
            $protocolosJson[] = array('id' => $protocolo->id, 'name' => $shortname, 'description' => $name);
        }

        $this->_helper->json($protocolosJson, true);
    }
}

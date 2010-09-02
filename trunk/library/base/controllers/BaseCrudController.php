<?php
/**
 * BaseCrudController: Controlador base para telas de CRUD
 * @author bruno
 * @package SGC
 */
abstract class BaseCrudController extends BaseController implements BaseCrudControllerInterface
{
    public $objeto;
    
    /**
     * Carrega a entidade no controlador
     * @return void
     */
    public function init()
    {
        parent::init();
        
        $entity = $this->getEntity();
        $this->objeto = new $entity();
    }
    
    /**
     * Retorna a Entidade à qual o controlador está relacionado
     * @return string
     */
    public function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::entity");
    }
    
    /**
     * Verirfica os parâmetros enviados pelo formulário da view de index
     * e redireciona para a devida action
     * @return void
     */
    public function verificarRequests()
    {
        if ($this->verificarRequestExclusaoMultipla()) {
            $this->forwardExcluir();
        } elseif ($this->verificarRequestPesquisa()) {
            $this->forwardPesquisar();
        }    
    }
    
    /**
     * Verifica se há um request de seleção múltipla do grid
     * @return boolean
     */
    public function verificarRequestExclusaoMultipla()
    {
        return ($this->getRequest()->isPost() AND $this->getRequest()->getParam('chkRegistro'));
    }
    
    /**
     * Verifica se há um request de pesquisa do grid
     * @return boolean
     */
    public function verificarRequestPesquisa()
    {
        return ($this->getRequest()->isPost() AND $this->getRequest()->getParam('valor'));
    }
    
    /**
     * Repassa para a action de pesquisar
     * @return void
     */
    public function forwardPesquisar()
    {
        $this->_forward('pesquisar', $this->getController(), 'default', $this->getRequest()->getPost());
    }
    
    /**
     * Repassa para a action de excluir
     * @return void
     */
    public function forwardExcluir()
    {
        $this->_forward('excluir', $this->getController(), 'default', $this->getRequest()->getPost());
    }
    
    /**
     * Carrega no grid da view os registros da entidade
     * @param $where
     * @return void
     */
    public function carregarGrid($where = NULL)
    {
        $this->view->lista = $this->objeto->listar($where);
    }
    
    /**
     * Index
     * @return void
     */
    public function indexAction()
    {    
        $this->verificarRequests();
        $this->carregarGrid();
        $this->setVariaveisIndex();
    }
    
    /**
     * Variáveis utilizadas pela view do index
     * @return void
     */
    public function setVariaveisIndex()
    {
        $this->view->objeto = $this->objeto;
    }
    
    /**
     * Pesquisar no Index
     * @return void
     */
    public function pesquisarAction()
    {
        $entity = $this->getEntity();
        $where = TUtils::getCondicaoPesquisa(
            $entity, 
            $this->getRequest()->getParam('campo'), 
            $this->getRequest()->getParam('operador'), 
            $this->getRequest()->getParam('valor')
        );
        $this->carregarGrid($where);
        $this->renderScript($this->getController() . '/index.phtml');
    }
    
    /**
     * Variáveis utilizadas pela view de formulário
     * @return void
     */
    public function setVariaveisFormulario()
    {
        $this->setObjetoView();
        $this->setIsEditView();
        $this->setIdView();
    }
    
    public function setObjetoView()
    {
        $this->view->objeto = $this->objeto;
    }
    
    public function setIsEditView()
    {
        $this->view->isEdit = ($this->objeto->getTableKeyValue()) ? true : false;
    }
    
    public function setIdView()
    {
        $this->view->id = $this->objeto->getTableKeyValue();
    }
    
    /**
     * Renderiza a view de formulário
     * @return void
     */
    public function renderFormScript()
    {
        $this->renderScript($this->getController() . '/editar.phtml');    
    }
    
    /**
     * Inserir
     * @return void
     */
    public function inserirAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $this->inserir();
                // Redirecionamento
                $this->redirectForm('insercao');
            }
            catch (SgcException $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        // Entrega as variáveis utilizadas pela view
        $this->setVariaveisFormulario();
        $this->renderFormScript();
    }
    
    public function inserir()
    {
        $this->objeto->updateAttributes($this->getRequest()->getPost());
        $this->objeto->salvar();
    }
    
    /**
     * Editar
     * @return void
     */
    public function editarAction()
    {
        //Re-instancia o objeto do controller ativo
        $this->objeto->__construct(NULL, $this->getRequest()->getParam($this->objeto->getTableKey(), NULL));
        
        if ($this->getRequest()->isPost()) {
            //Verifica se o botão clicado foi Excluir
            $this->verificarEditarExcluir();
            try {
                $this->alterar();
                //Redirecionar
                $this->redirectForm('alteracao');
            }
            catch (SgcException $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        // Entrega as variáveis utilizadas pela view
        $this->setVariaveisFormulario();
        $this->renderFormScript();
    }
    
    public function alterar()
    {
        $this->objeto->updateAttributes($this->getRequest()->getPost());
        $this->objeto->salvar();
    }
    
    /**
     * Excluir
     * @return void
     */
    public function excluirAction()
    {
        $entity = $this->getEntity();
        $objeto = new $entity();
        $entityKey = $objeto->getTableKey();
        
        try {
            if ($this->getRequest()->getParam('chkRegistro')) {
                $registros = $this->getRequest()->getParam('chkRegistro', NULL);
                
                $objeto = new $entity();
                $objeto->excluir($registros);
                
                $method = 'exclusoes';
            } elseif ($this->getRequest()->getParam($entityKey)) {
                $id = $this->getRequest()->getParam($entityKey, NULL);
                $objeto = new $entity(NULL, $id);
                $objeto->excluir();
                
                $method = 'exclusao';
            } else {
                $method = 'notFound';
            }
            $this->_helper->redirector('index', $this->getController(), 'default', array('method' => $method));
        }
        catch (SgcException $e) {
            $this->setErrorMessage($e->getMessage());
        } 
    }
    
    /**
     * Redireciona para a página de formulário
     * @return void
     */
    public function redirectForm($method)
    {
        $entityKey = $this->objeto->getTableKey();
        $this->_helper->redirector(
            'editar', 
            $this->getController(), 
            'default',
            array($entityKey => $this->objeto->getTableKeyValue(), 'method' => $method)
        );
    }
    
    public function verificarEditarExcluir()
    {
        $entityKey = $this->objeto->getTableKey();
        if ($this->getRequest()->getParam('btnAcao', NULL) == 'Excluir') {
            $this->_helper->redirector(
                'excluir', 
                $this->getController(), 
                'default',
                array($entityKey => $this->objeto->getTableKeyValue())
            );
        }
    }
}
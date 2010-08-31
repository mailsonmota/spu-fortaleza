<?php
class DataTablePagination {
    
    private $_page;
    private $_recordsPerPage = 20;
    private $_numberOfRecords;
    
    public function __construct() {
        $this->_page = $this->getCurrentPageNumber();
    }
    
    protected function getCurrentPageNumber()
    {
        return $this->getRequest()->getParam('pagina', 1);
    }
    
    public function setCurrentPageNumber($value)
    {
        $this->_page = $value;
    }
    
    public function setNumberOfRecords($value)
    {
        $this->_numberOfRecords = $value;
    }
    
    public function setRecordsPerPage($value)
    {
        if ($value != null) {
            $this->_recordsPerPage = $value;
        }
    }
    
    /**
     * firstItemIndex
     * @return int
     */
    public function getFirstRecordIndex()
    {
        return ($this->_page - 1) * $this->_recordsPerPage;
    }
    
    /**
     * lastItemIndex
     * @return int
     */
    public function getLastRecordIndex()
    {
        return ($this->_page * ($this->_recordsPerPage) > $this->_numberOfRecords) ? 
            $this->_numberOfRecords : 
            ($this->_page * $this->_recordsPerPage);
    }
    
    public function render()
    {
        $page = $this->_page;
        $recordsPerPage = $this->_recordsPerPage;
        $numberOfRecords = $this->_numberOfRecords;
        $ultimoItem = $this->getLastRecordIndex();
        
        // Primeira Página da Lista
        $primeiraPagina = ($page > 2) ? $page - 2 : 1;
        
        // Total de Páginas da Lista
        $totalPaginas = floor($numberOfRecords / $recordsPerPage);
        if ($numberOfRecords % $recordsPerPage > 0) {
            $totalPaginas++;  
        }
        
        // Última Página
        if ($primeiraPagina + 4 > $totalPaginas) {
            $ultimaPagina = $totalPaginas;
            $primeiraPaginaOriginal = $primeiraPagina;
            for ($i = 1; $i < 5 - ($ultimaPagina - $primeiraPaginaOriginal); $i++) {
                if ($primeiraPagina > 1) {
                    $primeiraPagina--;    
                } else {
                    break;    
                }
            }
        } else {
            $ultimaPagina = $primeiraPagina + 4;   
        }

        $html = '';
        
        if ($page > 1) {
            $firstPageUrl = $this->getPageUrl(1);
            $html .= "<a href='$firstPageUrl'>«« Primeira</a>";               
        } else {
            $html .= '&nbsp;';
        }
        
        if ($page > 1) {
            $previousPageUrl = $this->getPageUrl($page - 1);
            $html .= " | <a class='anterior' href='$previousPageUrl'>« Anterior</a>&nbsp;";
        }                
        for ($i = $primeiraPagina; $i <= $ultimaPagina; $i++) {
            if ($page > 1 OR $i > 1) {
                $html .= ' | ';
            }   
            $pageUrl = $this->getPageUrl($i);
            $html .= ($i != $page) 
                ? "<a href='$pageUrl'>$i</a>" : 
                '<span class="paginaAtual">'.$i.'</span>';
        }
        if ($ultimoItem < $numberOfRecords) {
            $nextPageUrl = $this->getPageUrl($page + 1);
            $html .= " | <a class='proximo' href='$nextPageUrl'>Próxima »</a>&nbsp;";
        }
        
        if ($ultimoItem < $numberOfRecords) {
            $lastPageUrl = $this->getPageUrl($totalPaginas);
            $html .= " | <a href='$lastPageUrl'>Última »»</a>";
        }
        $html .= "</td>";
        $html .= "</tr>";
        
        return $html;
    }
    
    protected function getPageUrl($pageNumber)
    {
        $controller = $this->getRequest()->getParam('controller');
        $action = $this->getRequest()->getParam('action');
        return $this->url(array('controller' => $controller, 'action' => $action, 'pagina' => $pageNumber));
    }
    
    protected function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
    
    /**
     * Retorna uma url valida
     * @param array $urlOptions
     * @param $name
     * @param $reset
     * @param $encode
     * @return String
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true) 
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }
    
    public function hasMoreThanOnePage()
    {
        return ($this->_numberOfRecords > $this->_recordsPerPage);
    }
}
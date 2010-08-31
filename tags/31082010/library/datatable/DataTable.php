<?php
/**
 * Data Table 
 * Componente de renderização de DataGrids
 * @author benrainir
 */
require_once('datatable/adapter/DataTableAdapterZend.php');
require_once('datatable/DataTableColumn.php');
require_once('datatable/DataTablePagination.php');
require_once('datatable/DataTableFilter.php');
class DataTable
{
    /**
     * Model
     * @var object
     */
    private $_model;
            
    /**
     * Html
     * @access private
     * @var string
     */
    private $html;
    
    /**
     * isPaginate
     * @var bool
     */
    private $isPaginate = TRUE;
        
    /**
     * Columns 
     * @var array
     */
    private $columns = array();
    
    /**
     * Data
     * @var array
     */
    private $data = array();

    /**
     * Key Column
     * @var array
     */
    private $keyColumn = array();
    
    /**
     * Indica se havera pesquisa no grid
     * @var bool
     */
    private $isSearchable = TRUE;
    
    private $order;
    
    private $_options = array();
    
    /**
     * Pagination Object
     * @var DataTablePagination
     */
    private $_pagination;
    
    /**
     * Filter Object
     * @var DataTableFilter
     */
    private $_filter;
    
    const ADAPTER = 'DataTableAdapterZend';
    
    /**
     * Construtor
     * @param $model
     * @param array $options
     */
    public function DataTable($model, $options = array())
    {
        $this->_model = $model;
        $this->_options = $options;
        $this->loadOptions();
        $this->loadPagination();
        
        return $this;
    }
    
    protected function loadOptions()
    {
        $this->loadTableKeyColumn();
        $this->loadIsSearchable();
        $this->loadCriteria();
        $this->loadColumns();
    }
    
    protected function loadTableKeyColumn()
    {
        $key = (isset($this->_options['key'])) ? $this->_options['key'] : $this->_model->getTableKey();
        $this->setKeyColumn($key);
    }
    
    public function setKeyColumn($value)
    {
        if (is_string($value)) {
            $this->keyColumn[] = $value;
        } elseif (is_array($value)) {
            $this->keyColumn = $value;
        }
        return $this;
    }
    
    protected function loadIsSearchable()
    {
        $this->isSearchable = (isset($this->_options['searchGrid'])) ? $this->_options['searchGrid'] : TRUE;
    }
    
    protected function loadCriteria()
    {
        $criteria = (isset($this->_options['criteria'])) ? $this->_options['criteria'] : NULL;
        $this->setCriteria($criteria);
    }
    
    public function setCriteria($criteria)
    {    
        $this->_filter = new DataTableFilter();
        $this->_filter->setModel($this->_model);
        $this->_filter->setCriteria($criteria);    
    }
    
    public function loadColumns()
    {
        $this->loadCheckboxColumn();
        $this->loadRadioColumn();
        $this->loadModelColumns();
        $this->loadActionColumn();
    }
    
    protected function loadModelColumns()
    {
        $this->addManyColumns($this->getColumnsFromModel());
    }
    
    /**
     * Add Many Columns
     * @param array
     */
    public function addManyColumns($columns)
    {
        if ($columns) {
            foreach ($columns as $col) {
                $this->addColumn($col);
            }
        }        
        return $this;
    }
    
    /**
     * Add Column
     * @param DataTableColumn
     */
    public function addColumn(DataTableColumn $column)
    {
        if ($this->numColumns() > 0) {
            $col =  $this->columns[$this->numColumns()-1];
            if ($col->getType() == DataTableColumn::COLUMN_TYPE_ACTION) {
                $this->columns[] = $col;
                $this->columns[$this->numColumns()-2] = $column;
            } else {
                $this->columns[] = $column;
            }
        } else {
            $this->columns[] = $column;
        }        
        return $this;
    }
    
    /**
     * Total de Colunas
     * @return int
     */
    public function numColumns()
    {
        return count($this->getColumns());
    }
    
    /**
     * get Column
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
    
    protected function getColumnsFromModel()
    {
        $columns = (isset($this->_options['columns'])) ? $this->_options['columns'] : array();
        
        if (is_array($columns)) {
            $i = 0;
            $fields = $columns ? $columns : $this->_model->getFields();
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    if ($field) {
                        $columns[$i] = new DataTableColumn($field);
                        if ($field == $this->keyColumn AND !$this->getShowKeyColumn()) {
                            $columns[$i]->setIsVisible(FALSE);
                        }
                        $i++;
                    }
                }
            }
        }
        
        return $columns;
    }
    
    protected function getShowKeyColumn()
    {
        return (isset($this->_options['showKeyColumn'])) ? $this->_options['showKeyColumn'] :false;
    }
    
    protected function loadCheckboxColumn()
    {
        if ($this->hasCheckboxColumn()) {
            $this->addCheckColumn($this->getHtmlInputColumn());
        }
    }
    
    protected function loadRadioColumn()
    {
        if ($this->hasRadioColumn()) {
            $this->addRadioColumn($this->getHtmlInputColumn());
        } 
    }
    
    protected function getHtmlInputColumn()
    {
        return (isset($this->_options['htmlInputColumn'])) ? $this->_options['htmlInputColumn'] : '';
    }
    
    protected function hasCheckboxColumn()
    {
        return (isset($this->_options['checkColumn'])) ? $this->_options['checkColumn'] : TRUE;
    }
    
    protected function hasRadioColumn()
    {
        return (isset($this->_options['radioColumn'])) ? $this->_options['radioColumn'] : FALSE;
    }
    
    protected function loadActionColumn()
    {
        if ($this->hasActionColumn()) {
            $this->addActionColumn();
        }
    }
    
    protected function hasActionColumn()
    {
        return (isset($this->_options['editColumn'])) ? $this->_options['editColumn'] : TRUE;
    }
    
    protected function addActionColumn()
    {
        $linkColumnTitle = $this->getLinkColumnTitle();
        $col = new DataTableColumn($linkColumnTitle, NULL, DataTableColumn::COLUMN_TYPE_ACTION, $linkColumnTitle);
        $col->setClass($this->getActionColumnClass());
        $key = $this->keyColumn[0];
        $col->setLink($this->getLinkColumnUrl($key));
        $col->setTarget($this->getActionColumnTarget());
        $this->columns[] = $col;
    }
    
    protected function getLinkColumnTitle()
    {
        return (isset($this->_options['editHeader'])) ? $this->_options['editHeader'] : 'Editar';
    }
    
    protected function getActionColumnClass()
    {
        return (isset($this->_options['editClass'])) ? $this->_options['editClass'] : 'edit';
    }
    
    protected function getLinkColumnUrl($key)
    {
        return $this->getAdapter()->makeUrl($this->getLinkColumnAction(), array($key));
    }
    
    protected function getLinkColumnAction()
    {
        return (isset($this->_options['editLink'])) 
            ? $this->_options['editLink'] 
            : $this->getAdapter()->getDefaultLinkColumnUrl();
    }
    
    protected function getLinkColumnDefaultAction()
    {
        return $this->getAdapter()->getCurrentUrl() . '/editar';
    }
    
    protected function getActionColumnTarget()
    {
        return (isset($this->_options['editTarget'])) ? $this->_options['editTarget'] : '';
    }
    
    protected function loadPagination()
    {
        $dataGridPagination = new DataTablePagination();
        $dataGridPagination->setRecordsPerPage($this->getRecordsPerPage());
        $dataGridPagination->setNumberOfRecords($this->numItens());
        
        $this->_pagination = $dataGridPagination;
    }
    
    /**
     * Render
     */
    public function render($reset = false)
    {
        $this->loadData();
        $this->loadPagination();
        $this->html  = $this->renderToolbar();
        $this->html .= '<table class="grid" cellspacing="1">';
        $this->html .= '<thead>';
        $this->html .= $this->renderHeader();
        $this->html .= $this->renderFilter();
        $this->html .= '</thead>';        
        $this->html .= $this->renderFooter();
        $this->html .= $this->renderBody();
        $this->html .= '</table>';
        if ($reset) {
            $this->columns = null;
        }
        return $this->html;
    }
    
    private function renderToolbar()
    {
        $html  = '';
        if ($this->isSearchable) {
            $html .= $this->getSearchButton();
        }        
        return $html;
    }
    
    protected function getSearchButton()
    {
        return ' 
            <button name="btnAcao" type="submit" value="Pesquisar" class="pesquisar default cancel">
                Pesquisar
            </button> ';
    }
    
    private function renderHeader()
    {
        $html = "<tr>";
        foreach ($this->getColumns() as $col) {
           $html .= $col->renderHeader();
        }       
        $html .= "</tr>";         
        return $html;
    }
    
    private function renderFilter()
    {
        $totalItens = $this->numItens();
        $html = '';                    
        if ($this->isSearchable) {
            $dat = $this->data;
            $html = "<tr class=\"tableFilter\">";
            foreach ($this->getColumns() as $col) {
               $html .= $col->renderFilter($this->data);        
            }       
            $html .= "</tr>";
        }                 
        return $html;
    }
    
    private function renderBody()
    {
        $totalItens   = $this->numItens();
        $primeiroItem = $this->firstItemIndex();
        $ultimoItem   = $this->lastItemIndex();
         
        $html = '';
        if ($totalItens > 0) {
            $html = '<tbody>';
            for ($i= $primeiroItem; $i < $ultimoItem ; $i++) {
                $odd = ($i % 2 != 0) ? 'class="odd"' : 'class="even"'; 
                $html .= "<tr $odd>";
                $registro = $this->data[$i];
                foreach ($this->getColumns() as $col) {
                    $html.= $col->renderBody($registro, $this->keyColumn);
                }
                $html.='</tr>';        
            }        
            $html .= '</tbody>';
        } else {
            $html = '<tbody><tr><td colspan="'.$this->numColumns().'">';
            $html .= $this->getEmptyDataMessage();
            $html .= '</td></tr></tbody>';
        }         
        return $html;
    }
    
    protected function getEmptyDataMessage() 
    {
        return (isset($this->_options['emptyMsg'])) 
            ? $this->_options['emptyMsg'] 
            : 'A pesquisa não retornou nenhum registro.';
    }
    
    private function renderFooter()
    {
        $html = '';
        if ($this->hasFooter()) {
            $totalItens   = $this->numItens(); 
            $primeiroItem = $this->firstItemIndex();
            $ultimoItem   = $this->lastItemIndex();
                
            $html  = '<tfoot>';                
            $html .= '<tr>';
            $html .= '<td colspan="'.$this->numColumns().'">';
            $primeiroExibindo = ($this->numItens() > 0) ? $primeiroItem + 1 : 0;
            $html .= "Exibindo de " . ($primeiroExibindo) . " à $ultimoItem de $totalItens registros.";
            $html .= "</td>";
            $html .= "</tr>";
            
            if ($this->isPaginate AND ($this->_pagination->hasMoreThanOnePage())) {
                $html .= "<tr class='paginacao'>";    
                $html .= "<td colspan='".$this->numColumns()."'>";
                $html .= $this->_pagination->render();
                $html .= "</td>";
                $html .= "</tr>";         
            }
            $html .= "</tfoot>";
        }                   
        
        return $html;
    }
    
    protected function getRecordsPerPage()
    {
        return (isset($this->_options['itensPerPage'])) ? $this->_options['itensPerPage'] : null;
    }
        
    public function hasFooter()
    {
        return (isset($this->_options['footer'])) ? $this->_options['footer'] : TRUE;
    }
    
    public function numItens()
    {
        return count($this->data);
    }
    
    /**
     * firstItemIndex
     * @return int
     */
    public function firstItemIndex()
    {
        return $this->_pagination->getFirstRecordIndex();
    }
    
    /**
     * lastItemIndex
     * @return int
     */
    public function lastItemIndex()
    {
        return $this->_pagination->getLastRecordIndex();
    }
    
    /**
     * Add Radio Column
     * @return DataTableColumn
     */
    public function addRadioColumn($atributoHtml='')
    {
        $col = new DataTableColumn('', NULL, DataTableColumn::COLUMN_TYPE_RADIO);
        
        $col->setAtributoHtml($atributoHtml);
        $this->columns[] = $col;
        return $this;
    }    
    
    /**
     * Add Check Column
     * @return DataTableColumn
     */
    public function addCheckColumn($atributoHtml='')
    {
        $col = new DataTableColumn('', NULL, DataTableColumn::COLUMN_TYPE_CHECK);
        
        $col->setAtributoHtml($atributoHtml);
        $this->columns[] = $col;
    }    

    public function getColumn($columnName)
    {
        return $this->columns[$columnName];
    }
    
    public function setIsPaginate($value)
    {
        $this->isPaginate = $value;
        return $this;
    }    

    /**
     * getIsPaginate
     * @return bool
     */
    public function getIsPaginate()
    {
        return $this->isPaginate;
    }    
    
    /**
     * getKeyColumn
     * @return array
     */
    public function getKeyColumn()
    {
        return $this->keyColumn;
    }
    
    protected function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }
    
    protected function getRequestData()
    {
        return $this->getRequest()->getPost();
    }
    
    public function loadData()
    {       
        $metodo = $this->getModelFetchMethod();
        $this->data = $this->_model->$metodo($this->_filter->getCriteria(), $this->order);
    }
    
    protected function getModelFetchMethod()
    {
        return (isset($this->_options['metodo'])) ? $this->_options['metodo'] : 'fetchAll';
    }
    
    public function addColumnsData($arrayColumnsName)
    {        
        foreach ($arrayColumnsName as $col) {
            $mask = NULL;
            if (is_string($col)) {
                $name = $col;
                $header = $col;
            }
            if (is_array($col)) {
                $name = $col['name'];
                $header = (isset($col['header'])) ? $col['header'] : $col['name'];
                $mask = (isset($col['mask'])) ? $col['mask'] : NULL;
            }
            $dataType = (isset($this->model->columnTypes) AND isset($this->model->columnTypes[$name])) ? 
                $this->model->columnTypes[$name] : 
                NULL;
                
            $tableColumn = new DataTableColumn($name, DataTableColumn::COLUMN_TYPE_DATA, $header, $dataType);
            
            if (!is_null($mask)) {
                $tableColumn->mask = $mask;
            }
            
            $this->addColumn($tableColumn);
        }
        return $this;
    }
    
    public function addFiltersCombo($arrayColumnsName)
    {
        if (is_array($arrayColumnsName)) {
            foreach ($arrayColumnsName as $col) {
                foreach ($this->getColumns() as $dtcol) {
                    if ($dtcol->getName() == $col) {
                        $dtcol->setFilterType(DataTableColumn::FILTER_TYPE_COMBOBOX);
                        
                        break;
                    }
                }            
            }
        }
        return $this;
    }
    
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
    
    public function addMask($columnName, $mask)
    {
        foreach ($this->getColumns() as $dtcol) {
            if ($dtcol->getName() == $columnName) {
                $dtcol->setMask($mask);
                break;
            }
        }
        return $this;
    }
    
    public static function getAdapter() {
        $className = self::ADAPTER;
        return new $className();
    }
}
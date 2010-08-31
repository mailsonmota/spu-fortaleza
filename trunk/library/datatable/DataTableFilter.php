<?php
class DataTableFilter
{
    private $_model;
    private $_criteria;
    private $_dataFilters;
    
    public function setModel($model)
    {
        $this->_model = $model;
    }
    
    public function setCriteria($criteria)
    {
        $tableName = $this->_model->getTablename();
        $this->loadDataFilters();
        if (!isset($criteria)) {
            $criteria = $this->prepareFilterSQL($this->_dataFilters, $tableName);
        } else {
            $sql = $this->prepareFilterSQL($this->_dataFilters, $tableName);
            
            if (!empty($sql)) {
                $criteria .= ' AND '.$sql ;
            }            
        }        
        
        $this->_criteria = $criteria ? $criteria : NULL;
    }
    
    protected function loadDataFilters()
    {
        #Codigo de Pesquisa referente a ação do botao pesquisar
        $formPesquisa = array();
        if ($this->hasFilterRequest()) {    
            foreach ($this->getRequestData() as $key => $value) {
                $pesquisa = array();
                if (strpos($key, 'fil_') !== FALSE AND (!empty($value))) {
                    // Quando o nome do campo tem espaços a pesquisa (POST) altera os espaços por underline, 
                    // estamos voltando para espaços.
                    $pesquisa['campo'] = str_replace('_', ' ', substr($key, 4, strlen($key)));
                    $pesquisa['valor'] = $this->prepareValueFilter($value);
                 
                    $formPesquisa[] = $pesquisa;
                }        
            }
        }
                
        $this->_dataFilters = $formPesquisa;
    }
    
    protected function hasFilterRequest()
    {
        return ($this->getAdapter()->hasPostData() AND $this->getAdapter()->getParameter('btnAcao') == 'Pesquisar');
    }
    
    protected function getRequestData()
    {
        return $this->getAdapter()->getPostData();
    }
    
    /**
     * Prepara o valor digitado (selecionado) no filtro de pesquisa removendo mascaras, fazendo uppercase, etc
     * @param $valor string|int
     * @return string|int
     */
    protected function prepareValueFilter($valor)
    {
        $valor = strtoupper($valor);
        return $valor;
    }
    
    protected function prepareFilterSQL(array $filterData, $table)
    {
        $sql = '';
        $flag = false;    
        foreach ($filterData as $data) {
            if (!empty($data['valor'])) {                          
                $sql .= ($flag)? ' AND ': '';     
                $sql .= $this->prepareOneFilterSQL($data['campo'], $data['valor'], $table);
              
                $flag = true;
            }              
        }        
        return $sql;     
    }
    
    protected function prepareOneFilterSQL($campo, $valor, $table)
    {
        $sql = ' ';
        if (trim($valor) <> "" && $campo <> "0") {
            if (strpos($campo, " ") > -1) {
                $sql .= '"' . $campo. '" ';
            } else {
                $sql .= $campo . " ";
            }
            
            $tipo = $this->getFieldType($campo);

            // Campo numérico e um valor não numérico, retornar nada
            if (($tipo == 'numeric' && !is_numeric($valor)) OR ($tipo == 'date' && !$this->is_date($valor))) {
                $sql .= "IS NULL AND $campo IS NOT NULL ";
            } else { 
                if ($tipo == 'string') {
                    $sql .= " ilike '%$valor%' ";
                } else {
                    if ($tipo == 'date') {
                        $sql .= " = '$valor' ";
                    } else {
                        $sql .= " = $valor ";
                    }
                }
            }
        }
        
        return $sql;
    }
    
    protected function getFieldType($field)
    {
        $fields = $this->_model->getFieldTypes();
        $tipo = $fields[$field];
        
        if ($tipo) {
            if (strpos($tipo, 'int') > -1) {
                return 'numeric';
            } elseif (strpos($tipo, 'varchar') > -1 OR strpos($tipo, 'text') > -1) {
                return 'string';
            } elseif (strpos($tipo, 'date') > -1) {
                return 'date';
            }
        }
    }
    
    public function getCriteria()
    {
        return $this->_criteria;
    }
    
    protected function is_date($valor)
    {
        return ($this->is_date_EN($valor) or $this->is_date_PT($valor));        
    }
        
    protected function is_date_EN($valor)
    {
        $valor = substr($valor, 0, 10);
        
        if (strlen($valor) != 10)
            return false;
        
        $dia = substr($valor, 8, 2);
        $mes = substr($valor, 5, 2);
        $ano = substr($valor, 0, 4);
        
        if (!is_numeric($dia) || !is_numeric($mes) || !is_numeric($ano))
            return false;
        
        return checkdate($mes, $dia, $ano);
    }
    
    protected function is_date_PT($valor)
    {
        $valor = substr($valor, 0, 10);
        
        if (strlen($valor) != 10)
            return false;
        
        $dia = substr($valor, 0, 2);
        $mes = substr($valor, 3, 2);
        $ano = substr($valor, 6, 4);
                    
        if (!is_numeric($dia) || !is_numeric($mes) || !is_numeric($ano))
            return false;
        
        return checkdate($mes, $dia, $ano);
    }
    
    protected function getAdapter()
    {
        return DataTable::getAdapter();
    }
}
?>
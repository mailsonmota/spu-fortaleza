<?php
/**
 * Classe base para os models de abstração de dados
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @since 09/06/2010
 */
abstract class BaseDao extends Zend_Db_Table_Abstract
{
    protected $_orderField;
    protected $_order = 'ASC';
    
    public function fetchAll(
        $where = NULL, 
        $order = NULL, 
        $count = NULL, 
        $offset = NULL
    )
    {
        $filtro = $this->filter();
        
        if ($filtro) {
            $where = (isset($where)) ? $where . ' AND ' . $filtro : $filtro;
        }
        
        if ($order == NULL) {
            $order = $this->getOrderField() . ' ' . $this->getOrder();
        }
        
        return parent::fetchAll($where, $order, $count, $offset);        
    }
    
    private function adjustData(array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->getAdjustedValue($value);
        }
        return $data;
    }
    
    protected function getAdjustedValue($value)
    {
        if ($value === false) {
            $value = 0;
        }
        return $value;
    }
    
    public function insert(array $data)
    {
        $data = $this->adjustData($data);
        return parent::insert($data);
    }
    
    public function update(array $data, $where)
    {
        $data = $this->adjustData($data);
        return parent::update($data, $where);
    }
    
    public function filter()
    {
        return NULL;
    }
    
    public function getTableName()
    {
        return $this->info('name');
    }
    
    public function getTableKey()
    {
        $primaryKeys = $this->info('primary');
        return array_pop($primaryKeys);
    }
    
    public function getOrder()
    {
        return $this->_order;
    }
    
    public function getOrderField()
    {
        if ($this->_orderField) {
            return $this->_orderField;
        }
        return $this->getTableKey();
    }
    
    public function getFields()
    {
        return $this->info('cols');
    }
    
    public function getFieldTypes()
    {
        $metadata = $this->info('metadata');
        $campos = $this->info('cols');
        
        $array = array();
        
        foreach ($campos as $campo) {
            $array[$campo] = $metadata[$campo]['DATA_TYPE'];
        }

        return $array;
    }
    
    public function getEmptyRow()
    {
        $data  = array(
            'table'    => $this,
            'rowClass' => $this->getRowClass(),
            'stored'   => true
        );
        
        $rowClass = $this->_rowClass;
        
        return new $rowClass($data);
    }
    
    /**
     * Verifica se existe algum resultado para o critério informado
     * 
     * @param $where critério Sql
     * @return boolean
     */
    public function exists($where = NULL)
    {
        $result = $this->fetchAll($where);
        return (count($result) > 0) ? true : false;
    }
    
    /**
     * Verifica se existe algum registro com a chave informada
     * 
     * @param $keyValue
     * @return boolean
     */
    public function keyValueExists($keyValue)
    {
        $result = $this->find($keyValue);
        return (count($result) > 0) ? true : false;
    }
}
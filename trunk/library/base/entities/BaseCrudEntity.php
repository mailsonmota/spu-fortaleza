<?php
/**
 * Classe base para a criação de entidades de CRUD
 * @author bruno
 * @package SGC
 * @since 18/12/2009
 */
abstract class BaseCrudEntity extends Zend_Db_Table_Row_Abstract implements BaseCrudEntityInterface
{
    protected $_dao;
    
    const ALL_UPPERCASE = TRUE;
    
    //TODO: Implementar Entidades/Models Dependentes
    //TODO: Métodos de Listar Estáticos
    
    public function __construct($config = NULL, $objectid = NULL)
    {
        if ($config == NULL) {
            $cols = $this->getDao()->info('cols');
                
            $data = array();
            foreach ($cols as $col) {
                $data[$col] = NULL;
            }
            $data = array('data' => $data);
            
            if (is_numeric($objectid)) {
                $result = $this->getDao()->find($objectid);
                if ($result) {
                    $resultArray = $result->toArray();
                    $data = array('data' => array_pop($resultArray));
                }
            }
            
            parent::__construct($data);
        } else {
            parent::__construct($config);
        }
        
        if (!$this->getTableKeyValue()) {
            $this->setDefaultValues();
        }
    }
    
    protected function setDefaultValues() {}
    
    public function getData()
    {
        $data = $this->toArray();
        return $data;
    }
    
    public function getTableName()
    {
        return $this->getDao()->getTableName();
    }
    
    public function getTableKey()
    {
        return $this->getDao()->getTableKey();
    }
    
    public function getTableKeyValue()
    {
        $tablekey = $this->getTableKey();
        return $this->_data[$tablekey];
    }
    
    public function setTableKeyValue($value)
    {
        $tablekey = $this->getTableKey();
        $this->_data[$tablekey] = $value;
    }
    
    /**
     * retorna o modelo Dao da Entidade
     * @return Zend_Db_Table
     */
    public function getDao()
    {
        $dao = 'Model_' . $this->_dao;
        return new $dao();    
    }
    
    public function getField($field)
    {
        return $this->_data[$field];
    }
    
    public function getFields()
    {
        return $this->getDao()->getFields();
    }
    
    public function setField($field, $value)
    {
        $this->_data[$field] = $value;
    }
    
    public function getDisplayName()
    {
        return $this->getField($this->getDisplayField());
    }
    
    public function getDisplayField()
    {
        return $this->getOrderField();
    }
    
    public function getOrderField()
    {
        return $this->getDao()->getOrderField();
    }
    
    public function getEntity()
    {
        return $this->getDao()->info('rowClass');
    }
    
    public function salvar()
    {
        $data = $this->getData();
        
        if ($this->getTableKeyValue() > 0) {
            $this->getDao()->update($data, $this->getTableKey() . ' = ' . $this->getTableKeyValue());
        } else {
            $key = $this->getDao()->insert($data);
            $this->setTableKeyValue($key);    
        }
    }
    
    public function excluir($array = NULL)
    {
        if ($array === NULL AND $this->getTableKeyValue()) {
            $where = $this->getTableKey() . ' = ' . $this->getTableKeyValue();
        } else {
            $where = $this->getTableKey() . ' IN (';
            foreach ($array as $a) {
                $where .= $a . ',';
            }
            $where = substr($where, 0, strlen($where) - 1) . ')';
        }
        $this->getDao()->delete($where);
    }
    
    /**
     * Busca no banco de dados
     * @param string $where
     * @param string $order
     * @param integer $count
     * @param integer $offset
     * @return Zend_Db_Table_Rowset
     */
    public function listar($where = NULL, $order = NULL, $count = NULL, $offset = 0){
        return $this->getDao()->fetchAll($where, $order, $count, $offset);
    }
    
    /**
     * Preenche os atributos do objeto a partir de um array associativo
     * @param $data
     * @param $modified
     * @return void
     */
    public function preencherAtributos($data, $modified = FALSE)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                if (array_key_exists($key, $this->_data)) {
                    $this->_data[$key] = $value;
                    if ($modified === TRUE) {
                        $this->_modifiedFields[$key] = TRUE;
                    }
                }
            }
        }
    }
    
    public function getCamposTipo()
    {
        return $this->getDao()->getFieldTypes();
    }    
    
    public function updateAttributes($data)
    {
        $this->preencherAtributos($data, TRUE);
    }
    
    public function filter()
    {
        return NULL;
    }
    
    public function getAsOptions($arrayOfObjects = null)
    {
        if (!$arrayOfObjects) {
            $arrayOfObjects = $this->listar();
        }
        
        $options = array();
        foreach ($arrayOfObjects as $object) {
            $options[$object->getTableKeyValue()] = $object->getDisplayName();
        }
        
        return $options; 
    }
}
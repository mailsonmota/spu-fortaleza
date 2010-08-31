<?php
/**
 * Data Table Column
 * Componente de renderização de colunas do DataGrid
 * @author benrainir
 */
class DataTableColumn
{
    /**
     * Visible
     * @var bool
     */
    private $isVisible = TRUE;
    
    /**
     * Class
     * @var string
     */
    private $class;
    
    /**
     * Name
     * @var string
     */
    private $name;
    
    /**
     * Header
     * @var string
     */
    private $header;
    
    /**
     * isFilter
     * @var bool
     */
    private $isFilter = TRUE;
    
    /**
     * Type
     * sType
     * date, numeric, strings(default), html 
     * @var string
     */
    private $type;
    
    /**
     * Link
     * @var string
     */
    private $link;
    
    /**
     * atributoHtml
     * @var string
     */
    private $atributoHtml;
    
    /**
     * Filter Type
     * @var int
     */
    public $filterType = 0;
    
    /**
     * Target
     * @var string
     */
    public $target = '';
    
    /**
     * Data Type
     * @var string
     */
    public $dataType;
    
    /**
     * Mascaras : são modificadores dos dados, como cpf e valores em R$.
     * @var int
     */
    public $mask = NULL;
    
    //Filter Types
    const FILTER_TYPE_INPUT = 0;
    const FILTER_TYPE_COMBOBOX = 1;
    
    //Mask Types
    const MASK_CPF = 1;
    const MASK_MONEY = 2;
    
    //Column Types
    const COLUMN_TYPE_DATA = 0;
    const COLUMN_TYPE_ACTION = 1;
    const COLUMN_TYPE_CHECK = 2;
    const COLUMN_TYPE_RADIO = 3;
    const COLUMN_TYPE_TEXT = 4;
    
    public function __construct(
        $name, 
        $header = NULL, 
        $type = DataTableColumn::COLUMN_TYPE_DATA, 
        $dataType = NULL, 
        $filterType = DataTableColumn::FILTER_TYPE_INPUT
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->filterType = $filterType;
        $this->dataType = $dataType;
                
        if ($header == NULL) {
            $this->setHeader($name);
        } else {
            $this->setHeader($header);
        }
    }
    
    public function renderHeader()
    {
        if ($this->isVisible === TRUE) {
            switch($this->getType()){
                case self::COLUMN_TYPE_CHECK:
                    return '<th class="colunaFixa">
                        <input 
                            type="checkbox" 
                            name="chk" 
                            onclick=\'javascript: checkedAll("chkRegistro[]", this.checked);\' 
                            />
                        </th>';
                    break;
                
                case self::COLUMN_TYPE_RADIO :
                    return '<th></th>';
                    break;
                
                default:
                    $align = 'left';
                    
                    $dataType = $this->getDataType();
                    if (strpos('numeric', $dataType) > -1) {
                       $align = 'right';
                    }

                    return '<th align="' . $align . '">
                        ' . $this->header . '</th>';
                    
                    break;
            }
        }        
    }

    //TODO: Filter utilizar como base a data não filtrada
    public function renderFilter($data)
    {
        $filterName = 'fil_'.$this->name;
        if (isset($_GET[$filterName])) {
            $valueFilter = $_GET[$filterName];
        } else {
            if (isset($_POST[$filterName])) {
                $valueFilter = $_POST[$filterName];
            } else {
                $valueFilter = '';
            }
        }
        if ($this->isVisible and $this->isFilter) {
            switch ($this->getType()){
                case self::COLUMN_TYPE_CHECK:
                    return '<th></th>';
                    break;
                
                case self::COLUMN_TYPE_RADIO :
                    return '<th></th>';
                    break;
                
                case self::COLUMN_TYPE_DATA :
                    if ($this->filterType == self::FILTER_TYPE_INPUT) {
                        if ($this->mask === self::MASK_CPF) {
                           return '<th>
                                <input 
                                    type="text" 
                                    size="12" 
                                    name="' . $filterName . '" 
                                    id="' . $filterName . '" 
                                    value="' . $valueFilter . '" 
                                    class="opcional maskCpf" />
                                </th>';
                        } else {
                            return '<th>
                                <input 
                                    type="text" 
                                    style="width:99%" 
                                    name="' . $filterName . '" 
                                    id="' . $filterName . '" 
                                    value="' . $valueFilter . '" 
                                    class="opcional " 
                                    title="Pressione Enter para pesquisar" />
                                </th>';
                        }
                        
                    } else {
                        $arrayColumnData = array();
                        $dataFiltered = array();
                        $count = count($data);
                        for ($i = 0; $i < $count; $i++) {
                            $registro = $data[$i];
                            $arrayColumnData[] = $registro[$this->name]; 
                        }                                           
                        
                        if (count($arrayColumnData) > 0) {
                            $dataFiltered = array_unique($arrayColumnData);
                            sort($dataFiltered);
                        }                        
                        
                        $html  = '<th>';
                        $html .= '<select name="fil_'.$this->name.'" ';
                        $html .= 'class="opcional">';
                        $html .= '<option value=""> - </option>';
                        foreach ($dataFiltered as $d) {
                            if ($d == $valueFilter) {
                                $html .= 
                                    '<option value="'.$d.'" selected title="'.$d.'">
                                        ' . $this->reduzirString($d, 25) . 
                                    '</option>';
                            } else {
                                $html .= 
                                    '<option value="'.$d.'" title="'.$d.'"> 
                                        ' . $this->reduzirString($d, 25) . 
                                    '</option>';
                            }
                        }
                        $html .= '</select>
                            </th>';
                        
                        return $html;
                    }                   
                    break;
                
                case self::COLUMN_TYPE_ACTION :
                    return '<th></th>';
                    break;
                
                default:
                    return '<th></th>';
                    break;
            }
        }        
    }
    
    public function renderBody($data, $keys)
    {         
         //Para as colunas Check e Radio calcula-se o value
         $valueExtraColumns = '';           
         $flag = TRUE;
         foreach ($keys as $k) {
             $valueExtraColumns .= ($flag) ? $data[$k] : ';' . $data[$k];
             $flag = FALSE;
         }  
               
         if ($this->isVisible) {
            switch ($this->getType()) {
                case self::COLUMN_TYPE_CHECK:
                    return '<td class="colunaFixa">
                        <input 
                            type="checkbox" 
                            name="chkRegistro[]" 
                            value="'.$valueExtraColumns.'" 
                            id="registro'. rand(1, 10000).'" 
                            ' . $this->atributoHtml.' />
                        </td>';
                    break;
                
                case self::COLUMN_TYPE_RADIO :
                    return '<td class="colunaFixa">
                        <input 
                            type="radio" 
                            name="chkRegistro" 
                            value="' . $valueExtraColumns . '" 
                            id="registro'. rand(1, 10000).'" 
                            ' . $this->atributoHtml . ' />
                        </td>';
                    break;
                
                case self::COLUMN_TYPE_DATA :
                   $dataValue = $data[$this->name];
                   $dataValue = ($this->is_date($dataValue)) ? TUtils::DataEnPt($dataValue) : $dataValue;
                   
                   if ($this->mask !== NULL) {
                       if ($this->mask === self::MASK_CPF) {
                           $dataValue = TUtils::NumToCpf($dataValue);
                       }
                       if ($this->mask === self::MASK_MONEY) {
                           $dataValue = TUtils::NumToMoney($dataValue, TRUE);
                       }
                   }
                   
                   $align='left';
                   $class = '';
                   
                   $dataType = $this->getDataType();
                   if (strpos('numeric', $dataType) > -1) {
                       $align = 'right';
                       $class = 'class="colunaNumerica"';
                   }
                   
                   return '<td align="' . $align . '" ' . $class . ' >' . $dataValue . '</td>';
                    break;
                
                case self::COLUMN_TYPE_ACTION :
                    $html  = '<td align="center" class="colunaFixa">';
                    $html .= '<a class="' . $this->class . '"';
                    if ($this->target) {
                        $html .= ' target="' . $this->target . '"';
                    }
                    if ($this->link) {
                        $html .= ' href="' 
                    	      . $this->link
                    	      . $valueExtraColumns 
                    	      . '"';
                    }
                    $html .= '>' .  $this->name . '</a></td>';
                    return $html;
                    break;
                
                case self::COLUMN_TYPE_TEXT:
                    return '<td class="colunaFixa">
                        <input 
                            class="opcional"
                            type="text" 
                            name="' . $data[$this->name] . '" 
                            id="registro'. rand(1, 10000).'" 
                            ' . $this->atributoHtml . ' />
                        </td>';
                    break;
                    
                default:
                    return '<td></td>';
                    break;
            }
         }      
    }

    public function setHeader($value)
    {
        $this->header = strtoupper($value);
    }
    public function getHeader()
    {
        return $this->header;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setIsVisible($value)
    {
        $this->isVisible = $value;
    }
        
    public function getIsVisible()
    {
        return $this->isVisible;
    }
    
    public function setIsFilter($value)
    {
        $this->isFilter = $value;
    }
        
    public function getIsFilter()
    {
        return $this->isFilter;
    }
    
    public function setClass($value)
    {
        $this->class = $value;
    }
      
    public function getClass()
    {
        return $this->class;
    }
    
    public function setType($value)
    {
        $this->type = $value;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setLink($value)
    {
        $this->link = $value;
    }
        
    public function getLink()
    {
        return $this->link;
    }
    
    public function setAtributoHtml($value)
    {
        $this->atributoHtml = $value;
    }
    
    public function getAtributoHtml()
    {
        return $this->atributoHtml;
    }   
    
    public function setFilterType($value)
    {
        $this->filterType = $value;
    }
    
    public function getFilterType()
    {
        return $this->filterType;
    }  

    public function setTarget($value)
    {
        $this->target = $value;
    }
    
    public function getTarget()
    {
        return $this->target;
    }  
    
    public function getDataType()
    {
        return $this->dataType;
    }
    
    public function setDataType($value)
    {
        $this->dataType = $value;
    }
    
    public function getMask()
    {
        return $this->mask;
    }
    
    public function setMask($value)
    {
        $this->mask = $value;
    }
    
    protected function reduzirString($string, $size = '30')
    {
        $tamanhoDaString = strlen($string);
        if ($tamanhoDaString <= $size) {
            return $string;
        }
        return substr($string, 0, $size - 3) . '...';
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
}
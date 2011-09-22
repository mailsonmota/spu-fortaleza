<?php
/**
 * Classe para criar XSD a partir de um array PHP. O XSD é criado com alguns
 * formatos definidos, pois o objetivo é que seja lido pelo xsdform.js
 * (especificar/referenciar detalhes do xsdforms.js)
 *
 * O array tem a seguinte estrutura:
 * TODO Documentar aqui a estrutura do array
 */
class XSDCreator
{
    /**
     * Cria o XSD
     *
     * @var array $data Array com as informações que gerarão o XSD. Ver
     * comentário da classe para informações sobre a estrutura do array.
     *
     * @return string Conteúdo do XSD
     */
    public static function create(array $data)
    {
        $data['xsdcreator_name'] = self::_adaptFormName($data['xsdcreator_name']);

        $xsd = self::_getHeadString($data);

        foreach (array_keys($data['xsdcreator_type']) as $i) {
            if (empty($data['xsdcreator_type'][$i])) {
                continue;
            }

            $required = isset($data['xsdcreator_required'][$i]) ? $data['xsdcreator_required'][$i] : null;

            switch ($data['xsdcreator_type'][$i]) {
            case 'string':
                $xsd .= self::_makeElement(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:string',
                                                  'required' => $required));
                break;
            case 'textarea':
                $xsd .= self::_makeElement(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:string',
                                                  'required' => $required));
                break;
            case 'integer':
                $xsd .= self::_makeElement(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:integer',
                                                  'required' => $required));
                break;
            case 'date':
                $xsd .= self::_makeElement(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:date',
                                                  'required' => $required));
                break;
            case 'select':
                $xsd .= self::_makeElementSelect(array('label' => $data['xsdcreator_label'][$i],
                                                         'options' => $data['xsdcreator_select_options'][$i]));
                break;
            }
        }

        $xsd .= self::_getBottomString();

        return $xsd;
    }

    /**
     * Cria elementos que gerarão campos numéricos, de texto, de textarea
     * e de data
     */
    private static function _makeElement(array $data)
    {
        $xsd = "<xs:element name='" . $data['label'] . "' ";
        $xsd .= "type='" . $data['type'] . "' ";
        $xsd .= $data['required'] == 'on' ? "minOccurs='1' " : "minOccurs='0' ";
        $xsd .= ">";
        $xsd .= self::_makeElementAnnotation($data['label']);
        $xsd .= "</xs:element>\n";

        return $xsd;
    }

    /**
     * Cria elementos que gerarão elementos do tipo select
     */
    private static function _makeElementSelect(array $data)
    {
        $xsdSelect = "<xs:element name='" . $data['label'] . "'>";
        $xsdSelect .= self::_makeElementAnnotation($data['label']);
        $xsdSelect .= "
              <xs:simpleType>
                <xs:restriction base='xs:string'>";

        foreach ($data['options'] as $option) {
            $xsdSelect .= "<xs:enumeration value='" . $option  . "' />";
        }

        $xsdSelect .= "
                </xs:restriction>
              </xs:simpleType>
            </xs:element>";

        return $xsdSelect;
    }

    /**
     * Cria a tag annotation que cada elemento deve ter
     */
    private static function _makeElementAnnotation($label)
    {
        return "
            <xs:annotation>
              <xs:appinfo>
                <xhtml:label>" . $label . "</xhtml:label>
              </xs:appinfo>
            </xs:annotation>";
    }

    /**
     * Retorna o cabeçalho do XSD
     */
    private static function _getHeadString(array $data)
    {
        return "
<xs:schema
   xmlns:xs='http://www.w3.org/2001/XMLSchema'
   xmlns:xhtml='http://www.w3.org/1999/xhtml' 
   targetNamespace='spu:" . $data['xsdcreator_name'] . "'
   elementFormDefault='qualified'>

  <xs:element
     name='form" . $data['xsdcreator_name'] . "'
     type='form" . $data['xsdcreator_name'] . "'>
    <xs:annotation>
      <xs:appinfo>
        <xhtml:label>" . $data['xsdcreator_name'] . "</xhtml:label>
      </xs:appinfo>
    </xs:annotation>
  </xs:element>

  <xs:complexType name='form" . $data['xsdcreator_name'] . "'>
    <xs:sequence>
      <xs:element name='identificacao'>
        <xs:annotation>
          <xs:appinfo>
            <xhtml:label>" . $data['xsdcreator_name'] . "</xhtml:label>
          </xs:appinfo>
        </xs:annotation>
        <xs:complexType>
          <xs:sequence>";
    }

    /**
     * Retorna o rodapé do XSD
     */
    private static function _getBottomString()
    {
        return "
          </xs:sequence>
        </xs:complexType>
      </xs:element>
    </xs:sequence>
  </xs:complexType>
</xs:schema>";
    }

    /**
     * Adapta o nome do formulário
     */
    private static function _adaptFormName($name)
    {
        return strtr($name,
                     array('Á' => 'A',
                           'á' => 'a',
                           'ã' => 'a',
                           'É' => 'e',
                           'é' => 'e',
                           'Í' => 'i',
                           'í' => 'i',
                           'Ó' => 'O',
                           'ó' => 'o',
                           'Ú' => 'u',
                           'ú' => 'u',
                           'Ç' => 'C',
                           'ç' => 'c'));
    }
}

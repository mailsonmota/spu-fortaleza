<?php
class XSDCreator {
    public static function create(array $data) {
        $data['xsdcreator_name'] = self::adapt_form_name($data['xsdcreator_name']);

        $xsd = self::_get_head_string($data);

        foreach (array_keys($data['xsdcreator_type']) as $i) {
            if (empty($data['xsdcreator_type'][$i])) {
                continue;
            }

            $required = isset($data['xsdcreator_required'][$i]) ? $data['xsdcreator_required'][$i] : null;

            switch ($data['xsdcreator_type'][$i]) {
            case 'string':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:string',
                                                  'required' => $required));
                break;
            case 'textarea':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:string',
                                                  'required' => $required));
                break;
            case 'integer':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:integer',
                                                  'required' => $required));
                break;
            case 'date':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:date',
                                                  'required' => $required));
                break;
            case 'select':
                $xsd .= self::_make_element_select(array('label' => $data['xsdcreator_label'][$i],
                                                         'options' => $data['xsdcreator_select_options'][$i]));
                break;
            }
        }

        $xsd .= self::_get_bottom_string();

        return $xsd;
    }

    private static function _make_element(array $data) {
        $xsd = "<xs:element name='" . $data['label'] . "' ";
        $xsd .= "type='" . $data['type'] . "' ";
        $xsd .= $data['required'] == 'on' ? "minOccurs='1' " : "minOccurs='0' ";
        $xsd .= ">";
        $xsd .= self::_make_element_annotation($data['label']);
        $xsd .= "</xs:element>\n";

        return $xsd;
    }

    private static function _make_element_select(array $data) {
        $xsd_select = "<xs:element name='" . $data['label'] . "'>";
        $xsd_select .= self::_make_element_annotation($data['label']);
        $xsd_select .= "
              <xs:simpleType>
                <xs:restriction base='xs:string'>";

        foreach ($data['options'] as $option) {
            $xsd_select .= "<xs:enumeration value='" . $option  . "' />";
        }

        $xsd_select .= "
                </xs:restriction>
              </xs:simpleType>
            </xs:element>";

        return $xsd_select;
    }

    private static function _make_element_annotation($label) {
        return "
            <xs:annotation>
              <xs:appinfo>
                <xhtml:label>" . $label . "</xhtml:label>
              </xs:appinfo>
            </xs:annotation>";
    }


    private static function _get_head_string(array $data) {
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

    private static function _get_bottom_string() {
        return "
          </xs:sequence>
        </xs:complexType>
      </xs:element>
    </xs:sequence>
  </xs:complexType>
</xs:schema>";
    }

    public static function adapt_form_name($name) {
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

<?php
class XSDCreator {
    public static function create(array $data) {
        $data['xsdcreator_name'] = self::adapt_form_name($data['xsdcreator_name']);

        $xsd = self::_get_head_string($data);

        for ($i = 0; $i < count($data['xsdcreator_type']); $i++) {
            switch ($data['xsdcreator_type'][$i]) {
            case 'string':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:string'));
                break;
            case 'integer':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:integer'));
                break;
            case 'textarea':
                $xsd .= self::_make_element(array('label' => $data['xsdcreator_label'][$i],
                                                  'type' => 'xs:string'));
                break;
            case 'date':
            }
        }

        $xsd .= self::_get_bottom_string();

        return $xsd;
    }

    private static function _make_element(array $data) {
        $minOccurs = $data['type'] == 'xs:integer' ? '' : "minOccurs='0' ";

        return "<xs:element "
            . "name='" . $data['label'] . "' "
            . "type='" . $data['type'] . "' "
            . $minOccurs
            . ">
              <xs:annotation>
                <xs:appinfo>
                  <xhtml:label>" . $data['label'] . "</xhtml:label>
                </xs:appinfo>
              </xs:annotation>
            </xs:element>\n";
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
            <xhtml:label>Elogio</xhtml:label>
          </xs:appinfo>
        </xs:annotation>
        <xs:complexType>
          <xs:sequence>
";
    }

    private static function _get_bottom_string() {
        return "
          </xs:sequence>
        </xs:complexType>
      </xs:element>
    </xs:sequence>
  </xs:complexType>
</xs:schema>
";
    }

    public static function adapt_form_name($name) {
        // TODO
        return 'adapt_form_name__' . $name;
    }
}

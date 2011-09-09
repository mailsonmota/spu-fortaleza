<?php
$alfresco_url = 'http://172.30.41.28:8080/alfresco/service/';
$alfresco_api = 'api/';
$alfresco_spu = 'spu/';
$login = 'gilsam';
$password = 'gilsam';

const PRIORIDADE_ID = 'fb12ce97-c50a-4df8-8a36-d7380f4a5c7f';
const PROTOCOLO_ORIGEM = '63c08421-ef1a-4f17-9cdf-571569ba3985'; // SAM 172.30.41.28

$protocolos_destino = get_protocolos_destino();
$tipos_processo = get_tipos_processo();

$counter = 0;

while ($counter < 2) {
    $counter++; print "\n" . $counter . "\n\n";
    foreach ($protocolos_destino as $protocolo_destino) {
        foreach ($tipos_processo as $tipo_processo => $assuntos) {
            foreach ($assuntos as $assunto) {
                print "protocolo_destino \t\t\t tipo processo \t\t\t\t assunto \n";
                print "$protocolo_destino \t $tipo_processo \t $assunto \n";
                $pre_processo = get_pre_processo(array('destino' => $protocolo_destino,
                                                       'tipo_processo' => $tipo_processo,
                                                       'assunto' => $assunto));

                try {
                    $abertura_resposta = curl_request($alfresco_url . $alfresco_spu . 'processo/abrir',
                                                      'POST',
                                                      array(CURLOPT_USERPWD => "$login:$password",
                                                            CURLOPT_POSTFIELDS => json_encode($pre_processo)));
                } catch (Exception $e) {
                    print "Exception: $e->getMessage()"; exit;
                }

                $processo_tmp = array_pop(array_pop(array_pop(array_pop(json_decode($abertura_resposta, true)))));

                $processo = get_processo(array('processo_id' => substr($processo_tmp['noderef'], 24),
                                               'destino_id' => $protocolo_destino,
                                               'prioridade_id' => PRIORIDADE_ID
                                               ));

                try {
                    $tramitacao_resposta = curl_request($alfresco_url . $alfresco_spu . 'processo/tramitar',
                                                        'POST',
                                                        array(CURLOPT_USERPWD => "$login:$password",
                                                              CURLOPT_POSTFIELDS => json_encode($processo)));
                } catch (Exception $e) {
                    print "Exception: $e->getMessage()";
                }

                $tramitacao_tmp = array_pop(array_pop(array_pop(array_pop(json_decode($tramitacao_resposta, true)))));
                print 'Processo criado: ' . $tramitacao_tmp['nome'] . "\n\n";
            }
        }
    }
}

/* ========================================================================== */

function get_protocolos_destino() {
    return array(
                 //'63c08421-ef1a-4f17-9cdf-571569ba3985', // SAM 172.30.41.28
                 '50a5562c-36c3-43b9-8d5b-67cfdb70eb87' // SME 172.30.41.28
                 //'6707cebc-8371-4e2b-a58f-cda52a8c9968' // SMS 172.30.41.28
                 );
}

function get_tipos_processo() {
    return array('ac1453fa-4ce8-4dea-86be-13aec970f1ce' => array('4bb1ff4c-68c4-4207-9411-63b4f4e5a60b', //// Aposentadoria // Voluntária, Magistér
                                                                 '2ebf1675-77ed-4f56-82de-560f4609f2c9' // Compulsória
                                                                 )
                 );
}

function get_pre_processo(array $ops = array()) {
    return $pre_processo = array("protocoloOrigem" => PROTOCOLO_ORIGEM,
                                 "tipoprocesso" => $ops['tipo_processo'],
                                 "assunto" => $ops['assunto'],
                                 "data" => "09/09/2011",
                                 "hora" => "11:41",
                                 "numeroOrigem" => "",
                                 "observacao" => "Criação automática. Script.",
                                 "destino" => $ops['destino'],
                                 "prioridadeId" => PRIORIDADE_ID,
                                 "dataPrazo" => "01/02/2003",
                                 "corpo" => "Criação automática. Script.",
                                 "folhas_quantidade" => "",
                                 "proprietarioId" => PROTOCOLO_ORIGEM,
                                 "manifestanteCpfCnpj" => "1234",
                                 "manifestanteNome" => "Gil",
                                 "manifestanteSexo" => "M",
                                 "manifestanteTipoId" => "5bab5b32-c3a9-460f-b0bb-6e9d8972a535",
                                 "responsavel" => "",
                                 "manifestanteOrganizacao" => "",
                                 "manifestanteLogradouro" => "",
                                 "manifestanteNumero" => "",
                                 "manifestanteComplemento" => "",
                                 "manifestanteBairroId" => "40fd86a4-5fcf-4770-a772-f0fba0887daa",
                                 "manifestanteCep" => "",
                                 "manifestanteCidade" => "",
                                 "manifestanteUf" => "CE",
                                 "manifestanteFoneResidencial" => "",
                                 "manifestanteFoneComercial" => "",
                                 "manifestanteFoneCelular" => "",
                                 "manifestanteObs" => "");
}

function get_processo(array $ops = array()) {
    return array("processoId" => $ops['processo_id'],
                 "destinoId" => $ops['destino_id'],
                 "prioridadeId" => $ops['prioridade_id'],
                 "prazo" => "01/02/2003",
                 'despacho' => 'Criado automaticamente. Script.');
}

function curl_request($url, $method, array $ops = array()) {
    $ch = curl_init();

    $ops[CURLOPT_CUSTOMREQUEST] = $method;
    $ops[CURLOPT_URL] = $url;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ops[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');

    if (!empty($ops)) {
        curl_setopt_array($ch, $ops);
    }

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}

// tests

/*$resposta = curl_request($alfresco_url . $alfresco_spu . 'processo/abrir', 'GET', array(CURLOPT_USERPWD => "$login:$password"));
  var_dump($resposta);exit;*/
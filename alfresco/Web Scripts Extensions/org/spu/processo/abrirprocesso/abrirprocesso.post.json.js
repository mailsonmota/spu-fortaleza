<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var tipoProcessoId = json.get("tipoprocesso")
var sigla = getSiglaFromTipoProcessoId(tipoProcessoId)

var props = new Array()

var numero = gerarNumeroProcesso(sigla)
props["spu:processo.Numero"] = numero

props["cm:title"] = numero

numero = numero.replace("/", "_");
props["cm:name"] = numero

var data = getDataFormatadaAlfresco(json.get("data"))
props["spu:processo.Data"] = data

props["spu:processo.Observacao"] = json.get("observacao")

var prioridadeId = json.get("prioridadeId")
var prioridadeNoderef = getNode(prioridadeId)
props["spu:processo.Prioridade"] = prioridadeNoderef

props["spu:processo.NumeroOrigem"] = json.get("numeroOrigem")

if (json.has("dataPrazo") && json.get("dataPrazo") != '') {
	props["spu:processo.DataPrazo"] = getDataFormatadaAlfresco(json.get("dataPrazo"))
}

/* BEGIN Dados do Manifestantes */

props["spu:manifestante.Nome"] = json.get("manifestanteNome")
props["spu:manifestante.Cpf"] = json.get("manifestanteCpfCnpj")
props["spu:manifestante.Sexo"] = json.get("manifestanteSexo")

var manifestanteTipoId = getNode(json.get("manifestanteTipoId"))
props["spu:manifestante.Tipo"] = manifestanteTipoId

var manifestanteBairroNoderef = getNode(json.get("manifestanteBairroId"))
props["spu:manifestante.Bairro"] = manifestanteBairroNoderef

props["spu:manifestante.Organizacao"] = json.get("manifestanteOrganizacao")
props["spu:manifestante.Logradouro"] = json.get("manifestanteLogradouro")
props["spu:manifestante.Numero"] = json.get("manifestanteNumero")
props["spu:manifestante.Complemento"] = json.get("manifestanteComplemento")
props["spu:manifestante.Cep"] = json.get("manifestanteCep")
props["spu:manifestante.Cidade"] = json.get("manifestanteCidade")
props["spu:manifestante.Uf"] = json.get("manifestanteUf")
props["spu:manifestante.FoneResidencial"] = json.get("manifestanteFoneResidencial")
props["spu:manifestante.FoneComercial"] = json.get("manifestanteFoneComercial")
props["spu:manifestante.Celular"] = json.get("manifestanteFoneCelular")
props["spu:manifestante.Obs"] = json.get("manifestanteObs")

/* END Dados do Manifestantes */

props["spu:processo.Corpo"] = json.get("corpo")
props["spu:processo.Origem"] = json.get("protocoloOrigem")
props["spu:processo.Assunto"] = json.get("assunto")
props["spu:processo.Proprietario"] = json.get("proprietarioId")

var processo = userhome.createNode(null, "spu:processo", props)

/* Folhas e Volumes */
if (json.has('folhas_quantidade') && json.get('folhas_quantidade') > 0) {
    var folhas = json.get('folhas_quantidade')
    
    if (json.has('folhas_volumes_nome') || json.has('folhas_volumes_inicio') || json.has('folhas_volumes_fim') || json.has('folhas_volumes_descricao')) {
        var volumes_nomes_json =      (json.has('folhas_volumes_nome'))      ? json.get('folhas_volumes_nome')      : null
        var volumes_inicios_json =    (json.has('folhas_volumes_inicio'))    ? json.get('folhas_volumes_inicio')    : null
        var volumes_fins_json =       (json.has('folhas_volumes_fim'))       ? json.get('folhas_volumes_fim')       : null
        var volumes_descricoes_json = (json.has('folhas_volumes_descricao')) ? json.get('folhas_volumes_descricao') : null

        var volumes_nomes =      eval('(' + volumes_nomes_json + ')')
        var volumes_inicios =    eval('(' + volumes_inicios_json + ')')
        var volumes_fins =       eval('(' + volumes_fins_json + ')')
        var volumes_descricoes = eval('(' + volumes_descricoes_json + ')')

        var volumes = arraysToVolumesJson(volumes_nomes, volumes_inicios, volumes_fins, volumes_descricoes)
    } else {
        var volumes = null
    }
    addUpdateFolhas(processo.properties['sys:node-uuid'], folhas, volumes)

    processo = getNode(processo.properties['sys:node-uuid'])
}

model.processo = processo

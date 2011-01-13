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

var dataPrazo = getDataFormatadaAlfresco(json.get("dataPrazo"))
props["spu:processo.DataPrazo"] = dataPrazo

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

props["spu:processo.Origem"] = json.get("origem")
props["spu:processo.Assunto"] = json.get("assunto")
props["spu:processo.Proprietario"] = json.get("proprietarioId")

var processo = userhome.createNode(null, "spu:processo", props)

model.processo = processo

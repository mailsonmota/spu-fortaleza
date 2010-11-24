<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var props = new Array()

var numero = json.get("numero")+""

props["cm:title"] = numero
numero = numero.replace("/", "_");
props["cm:name"] = numero

var d = getDataFormatadaAlfresco(json.get("data"))
props["spu:processo.Data"] = d

props["spu:processo.Observacao"] = json.get("observacao")

var prioridadeId = json.get("prioridadeId")
var prioridadeNoderef = getNode(prioridadeId)
props["spu:processo.Prioridade"] = prioridadeNoderef

props["spu:processo.NumeroOrigem"] = json.get("numeroOrigem")

var dataPrazo = json.get("dataPrazo")
var dp = getDataFormatadaAlfresco(json.get("dataPrazo"))
props["spu:processo.DataPrazo"] = dp

props["spu:processo.ManifestanteNome"] = json.get("manifestanteNome")
props["spu:processo.ManifestanteCpf"] = json.get("manifestanteCpfCnpj")

var manifestanteTipoId = getNode(json.get("manifestanteTipoId"))
props["spu:processo.ManifestanteTipo"] = manifestanteTipoId

var manifestanteBairroNoderef = getNode(json.get("manifestanteBairroId"))
props["spu:processo.ManifestanteBairro"] = manifestanteBairroNoderef

props["spu:processo.Corpo"] = json.get("corpo")
props["spu:processo.Despacho"] = json.get("corpo")

var node = userhome.createNode(null, "spu:processo", props)

var idAssunto = json.get("assunto")
var nodeAssunto = getNode(idAssunto)
node.createAssociation(nodeAssunto, "spu:processo.Assunto")

var idProprietario = json.get("proprietarioId")
var nodeProprietario = getNode(idProprietario)
node.createAssociation(nodeProprietario, "spu:processo.Proprietario")

var idOrigem = json.get("origem")
var nodeOrigem = getNode(idOrigem)
node.createAssociation(nodeOrigem, "spu:processo.Origem")

var idDestino = json.get("destino")
var nodeDestino = getNode(idDestino)
node.createAssociation(nodeDestino, "spu:processo.Destino")

model.processo = node

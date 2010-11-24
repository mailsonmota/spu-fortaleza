<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processoId = json.get("processoId")
var protocoloId = json.get("destinoId")
var prioridadeId = json.get("prioridadeId")
var prazo = new String(json.get("prazo"))
var despacho = json.get("despacho")
var origemId

var processo = getNode(processoId)

if (protocoloId == processo.assocs['spu:processo.Destino'][0].properties['sys:node-uuid']) {
	origemId = processo.assocs['spu:processo.Origem'][0].properties['sys:node-uuid']
} else {
	origemId = processo.assocs['spu:processo.Destino'][0].properties['sys:node-uuid']
}

processo = tramitar(processoId, origemId, protocoloId, prioridadeId, prazo, despacho)
model.processo = processo

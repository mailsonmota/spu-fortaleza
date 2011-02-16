<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processoId = json.get("processoId")
var protocoloId = json.get("destinoId")
var prioridadeId = json.get("prioridadeId")
var prazo = new String(json.get("prazo"))
var despacho = json.get("despacho")
var origemId
var copias = (json.has("copias")) ? json.get("copias") : null

var processo = getNode(processoId)

if (protocoloId == processo.properties['spu:processo.Destino']) {
	origemId = processo.properties['spu:processo.Origem']
} else {
	origemId = processo.properties['spu:processo.Destino']
}

var processos = new Array();
processos[0] = processoId;

if (copias) {
    criarCopias(processos, copias)
}

processo = tramitar(processoId, protocoloId, prioridadeId, prazo, despacho)
model.processo = processo

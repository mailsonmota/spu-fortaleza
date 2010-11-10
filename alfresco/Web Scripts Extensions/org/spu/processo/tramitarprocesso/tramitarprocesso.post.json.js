<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processoId = json.get("processoId")
var protocoloId = json.get("destinoId")
var prioridadeId = json.get("prioridadeId")
var prazo = json.get("prazo")
var despacho = json.get("despacho")

var processo = getNode(processoId)
var origemId = processo.properties['spu:processo.Destino']

processo = tramitar(processoId, origemId, protocoloId, prioridadeId, prazo, despacho)
model.processo = processo

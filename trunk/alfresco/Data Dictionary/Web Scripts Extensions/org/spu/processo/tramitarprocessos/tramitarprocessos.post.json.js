<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processos = json.get("processos")
var destinoId = json.get("destinoId")
var despacho = json.get("despacho")
var copias = (json.has("copias")) ? json.get("copias") : null

processosTramitados = tramitarProcessos(processos, destinoId, despacho)
if (copias) {
    criarCopias(processos, copias)
}
model.processos = processosTramitados

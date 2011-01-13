<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processos = json.get("processos")
var destinoId = json.get("destinoId")
var despacho = json.get("despacho")

processos = tramitarProcessos(processos, destinoId, despacho)
model.processos = processos

<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processos = json.get("processos")
var despacho = json.get("despacho")

processos = tramitarExternos(processos, despacho)
model.processos = processos

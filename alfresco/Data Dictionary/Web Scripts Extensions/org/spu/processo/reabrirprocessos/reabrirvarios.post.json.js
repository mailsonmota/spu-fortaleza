<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processosParaReabrir = json.get("processos")
var despacho = json.get("despacho")
processos = reabrirProcessos(processosParaReabrir, despacho)
model.processos = processos

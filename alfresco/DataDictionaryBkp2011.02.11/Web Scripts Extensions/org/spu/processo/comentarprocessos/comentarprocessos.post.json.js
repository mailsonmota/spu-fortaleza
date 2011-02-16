<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processosParaComentar = json.get("processos")
var despacho = json.get("despacho")
processos = comentarProcessos(processosParaComentar, despacho)
model.processos = processos

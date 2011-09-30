<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processosParaArquivar = json.get("processos")
var despacho = json.get("despacho")
var statusArquivamento = json.get("statusArquivamento")
var motivo = json.get("motivo")
var local = json.get("local")
var pasta = json.get("pasta")

processos = arquivarProcessos(processosParaArquivar, despacho, statusArquivamento, motivo, local, pasta)
model.processos = processos

<import resource="/Company Home/Data Dictionary/Scripts/SPU/tipoprocesso.js">
var idTipoProcesso = url.extension
var assuntos = getAssuntosPorTipoProcesso(idTipoProcesso)
model.assuntos = assuntos

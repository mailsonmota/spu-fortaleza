<import resource="/Company Home/Data Dictionary/Scripts/SPU/tipoprocesso.js">
var nomeTipoProcesso = url.extension
var assuntos = getAssuntosPorTipoProcesso(nomeTipoProcesso)
model.assuntos = assuntos

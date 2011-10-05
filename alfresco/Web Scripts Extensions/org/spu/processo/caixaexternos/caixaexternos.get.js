<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var params = url.extension.split('/')
var offset = params[0]
var pageSize = params[1]
var filter = params[2]
var assuntoId = args['assunto-id']

model.processos = getCaixaExternos(offset, pageSize, filter, assuntoId)

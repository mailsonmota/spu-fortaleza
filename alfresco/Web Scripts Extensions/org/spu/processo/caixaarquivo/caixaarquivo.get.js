<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var params = url.extension.split('/')
var offset = params[0]
var pageSize = params[1]
var filter = params[2]
var assuntoId = args['assunto-id']

model.processos = getCaixaArquivo(offset, pageSize, filter, assuntoId)

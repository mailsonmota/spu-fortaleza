<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

var params = url.extension.split('/')
var offset = params[0]
var pageSize = params[1]
var filter = args['s']

var protocolos = getTodosProtocolosPaginado(offset, pageSize, filter)
model.protocolos = protocolos

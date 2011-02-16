<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

offset = args['iDisplayStart']
pageSize = (args['iDisplayLength']) ? args['iDisplayLength'] : 20
filter = (args['sSearch']) ? args['sSearch'] : ''

var protocolos = getTodosProtocolosPaginado(offset, pageSize, filter)
model.protocolos = protocolos

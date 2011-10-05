<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

var protocoloRaizId = url.extension.split('/')[0]
var protocoloOrigemId = args['protocolo-origem-id']

model.protocolos = getProtocolosUnder(protocoloRaizId, protocoloOrigemId)


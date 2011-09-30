<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

var url = url.extension.split('/')
var protocoloRaizId = url[0]

model.protocolos = getProtocolosUnder(protocoloRaizId)


<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

var protocoloOrigemId = args['protocolo-origem-id']
var tipoProcessoId = args['tipo-processo-id']

var protocolos = getProtocolosRaiz(protocoloOrigemId, tipoProcessoId)
model.protocolos = protocolos

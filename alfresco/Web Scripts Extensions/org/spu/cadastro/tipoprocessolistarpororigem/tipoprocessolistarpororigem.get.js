<import resource="/Company Home/Data Dictionary/Scripts/SPU/tipoprocesso.js">
var origem = url.extension // Modificar

var tiposProcesso = getTiposProcesso()

var tiposProcessoResultado = new Array()

for each (tipoProcesso in tiposProcesso) {
    isAuthorized = false
    if (tipoProcesso.assocs['spu:tipoprocesso.Protocolos']) {
        for each (protocolo in tipoProcesso.assocs['spu:tipoprocesso.Protocolos']) {
            if (protocolo.properties['sys:node-uuid'] == origem) {
                isAuthorized = true
            }
        }
    } else {
        isAuthorized = true
    }
    
    if (isAuthorized == true ) {
        tiposProcessoResultado.push(tipoProcesso)
    }
}

model.tiposProcesso = tiposProcessoResultado

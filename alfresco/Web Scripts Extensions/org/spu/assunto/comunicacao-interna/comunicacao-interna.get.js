<import resource="/Company Home/Data Dictionary/Scripts/SPU/assunto.js">
var assuntoId = url.templateArgs['uuid']

var assunto = getAssunto(assuntoId)

var resultado = null

// verificar se tem um .odt com nome
for each (child in assunto.children) {
    if (child.name.search(/^comunicacao-interna.*\.odt$/) > -1) {
        resultado = child.properties['sys:node-uuid']
    }
}

model.resultado = resultado

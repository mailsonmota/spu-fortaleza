<import resource="/Company Home/Data Dictionary/Scripts/SPU/assunto.js">

var xml = json.get('processedXml')
var nodeId = json.get('nodeId')

var processo = getNode(nodeId)

var respostas = processo.createNode('respostasFormulario.xml', 'spu:respostasFormulario')
respostas.content = xml
respostas.save()

model.mensagem = "sucesso"

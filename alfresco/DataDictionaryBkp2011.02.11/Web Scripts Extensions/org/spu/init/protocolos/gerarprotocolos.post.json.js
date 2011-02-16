<import resource="/Company Home/Data Dictionary/Scripts/SPU/criarprotocolos.js">

var estrutura = json.get("estrutura")

model.mensagem = "Resultado: " + gerarProtocolos(estrutura)

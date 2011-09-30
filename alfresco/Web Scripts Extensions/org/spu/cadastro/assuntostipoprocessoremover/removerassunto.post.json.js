<import resource="/Company Home/Data Dictionary/Scripts/SPU/assunto.js">


var assuntoIdArray = new Array()

var stringIdAssunto = json.get("assuntos")

assuntoIdArray = eval('(' + stringIdAssunto + ')')

removerAssunto(assuntoIdArray)

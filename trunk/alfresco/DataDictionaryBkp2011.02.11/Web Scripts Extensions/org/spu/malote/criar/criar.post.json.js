<import resource="/Company Home/Data Dictionary/Scripts/SPU/malote.js">

var descMalote = json.get("desc")
var tipo = json.get("tipo")

var malote = criarMalote(descMalote, tipo)

model.malote = malote

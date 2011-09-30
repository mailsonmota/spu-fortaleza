<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processoId = json.get('processoId')
var folhas = json.get('folhas')
var volumes = json.get('volumes')

if (addUpdateFolhas(processoId, folhas, volumes)) {
    model.resultado = true
} else {
    model.resultado = false
}

<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var params = new Array()

if (json.has("numero")) {
    var numero = new Array()
    numero['key'] = 'Numero'
    numero['value'] = json.get("numero")
    params.push(numero);
}

if (json.has("any")) {
    var any = new Array()
    any['key'] = 'any'
    any['value'] = json.get("any")
    params.push(any);
}

processos = consultar(params)
model.processos = processos

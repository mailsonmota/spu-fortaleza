<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processosParaReceber = json.get("processos")
processos = receberVarios(processosParaReceber)
model.processos = processos

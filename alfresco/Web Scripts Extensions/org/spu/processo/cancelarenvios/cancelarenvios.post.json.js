<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processos = json.get("processosParaCancelarEnvio")

processos = cancelarEnvios(processos)
model.processos = processos

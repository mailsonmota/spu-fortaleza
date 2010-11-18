<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var propriedades = new Array()

var numero = json.get("numero")

propriedades['title'] = numero
propriedades['name'] = numero.replace("/", "_")
propriedades['data'] = getDataFormatadaAlfresco(json.get("data"))
propriedades['observacao'] = json.get("observacao")
propriedades['prioridadeId'] = json.get("prioridadeId")
propriedades['numeroOrigem'] = json.get("numeroOrigem")
propriedades['dataPrazo'] = getDataFormatadaAlfresco(json.get("dataPrazo"))
propriedades['manifestanteNome'] = json.get("manifestanteNome")
propriedades['manifestanteCpfCnpj'] = json.get("manifestanteCpfCnpj")
propriedades['manifestanteTipoId'] = json.get("manifestanteTipoId")
propriedades['manifestanteBairroId'] = json.get("manifestanteBairroId")
propriedades['origem'] = json.get("origem")
propriedades['destino'] = json.get("destino")
propriedades['corpo'] = json.get("corpo")
propriedades['assuntoId'] = json.get("assunto")
propriedades['proprietarioId'] = json.get("proprietarioId")

var processo = abrirProcesso(propriedades)

model.processo = processo

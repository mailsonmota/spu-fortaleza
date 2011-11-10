<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var params = new Array()

function addFilterParam(jsonField, key, arrayToPush) {
    if (json.has(jsonField)) {
        var filterParam = new Array()
        filterParam['key'] = key
        filterParam['value'] = json.get(jsonField)
        arrayToPush.push(filterParam);
    }
}

/* Properties */
addFilterParam('numero', 'spu:processo.Numero', params)
addFilterParam('assunto', 'spu:processo.Assunto', params)
addFilterParam('proprietario', 'spu:processo.Proprietario', params)
addFilterParam('protocolo', 'spu:processo.Protocolo', params)
addFilterParam('data', 'spu:processo.Data', params)
addFilterParam('envolvido', 'spu:manifestante.Nome', params)
addFilterParam('corpo', 'spu:processo.Corpo', params)
addFilterParam('status', 'spu:processo.Status', params)
addFilterParam('observacao', 'spu:processo.Observacao', params)

/* Special Filters */
addFilterParam('any', 'any', params)
addFilterParam('tipoprocesso', 'TipoProcesso', params)
addFilterParam('volume', 'Volume', params)

processos = consultar(params, json.get('offset'), json.get('pageSize'))
model.processos = processos

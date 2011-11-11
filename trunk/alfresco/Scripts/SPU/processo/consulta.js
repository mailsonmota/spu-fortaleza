/**
 * Consulta
 * 
 * Métodos para consultar os processos do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 07/12/2010
*/
function consultar(params, offset, pageSize) {
    var searchQuery = 'TYPE:"spu:processo" AND NOT(ISNULL:"spu:processo.Destino")'
    var queryAdicional = ''
    for (var i=0; i < params.length; i++) {
        if (params[i]['value'] != '') {
    		campo = getFilterParamField(params[i]['key'])
    		value = getFilterParamValue(params[i]['key'], params[i]['value'])
    		if (campo) {
        		queryAdicional += ' AND ' + campo + ':"' + value + '"'
        	}

            if (params[i]['key'] == 'Volume') {
               queryAdicional += ' AND NOT(ISNULL:"spu:folhas.Volumes")'
            }
        }
    }

    if (queryAdicional == '') {
        return new Array
    } else {
        searchQuery += queryAdicional
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

    return processos;
}

function getFilterParamField(paramKey) {
    var field = '';
    if (isPropertyFilterParam(paramKey)) {
        field = getAdjustedPropertyParamToField(paramKey)
    } else if (paramKey == 'all') {
        field = 'ALL'
    } else if (paramKey == 'any') {
        field = 'ALL'
    }

    return field
}

function isPropertyFilterParam(paramKey) {
    return (paramKey.indexOf(':') > 0) ? true : false
}

function getAdjustedPropertyParamToField(paramKey) {
    return '@' + paramKey.replace(':', '\\:')
}

function getFilterParamValue(paramKey, paramValue) {
	if (paramKey == 'spu:processo.Status') {
		paramValue = 'workspace://SpacesStore/' + paramValue
	} else if(paramKey == 'spu:processo.Data') {
        paramValue = getDataPtEn(paramValue)
    } else if (paramKey == 'spu:folhas.Volumes') {
        paramValue = '*'
    }
    
	return paramValue
}

function getDataPtEn(data) {
    var ano = data.substring(6)
    var mes = data.substring(3, 5)
    var dia = data.substring(0, 2)
    
    return ano + '-' + mes + '-' + dia
}

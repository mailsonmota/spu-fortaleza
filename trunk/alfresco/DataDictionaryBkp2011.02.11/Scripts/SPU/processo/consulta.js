/**
 * Consulta
 * 
 * Métodos para consultar os processos do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 07/12/2010
*/
function consultar(params) {
    var query = 'TYPE:"spu:Processo"'
    for (var i=0; i < params.length; i++) {
        field = (params[i]['key'] == 'any') ? 'ALL' : '@spu\\:processo.' + params[i]['key']
        query += ' AND ' + field + ':"' + params[i]['value'] + '"'  
    }

    var processos = search.luceneSearch("workspace://SpacesStore", query)	

    return processos
}

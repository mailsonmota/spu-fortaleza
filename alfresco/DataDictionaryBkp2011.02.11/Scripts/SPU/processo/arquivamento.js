/**
 * Arquivamento
 * 
 * Métodos para gerenciar os arquivamentos do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 29/11/2010
*/
function getCaixaArquivo(offset, pageSize, filter) {
	var caixasArquivo = getCaixasArquivo()
	var caixaArquivo
	var processos = new Array()
	var path

    searchQuery = '+TYPE:"spu:processo" AND (';
    for (var i=0; i < caixasArquivo.length;i++) {
		caixaArquivo = caixasArquivo[i]
		path = caixaArquivo.getQnamePath()

        if (i != 0) { 
            searchQuery += ' OR ' 
        }
        searchQuery += 'PATH:"' + path + '//*"'
	}
    searchQuery += ')'
    if (filter && filter != '') {
        searchQuery += ' +ALL:"*' + filter + '*"';
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

	return processos
}

function arquivarProcesso(nodeId, despacho, statusArquivamento, motivo, local, pasta) {
	var processo = getNode(nodeId)
    despacho = (!despacho) ? null : despacho    

    /* Salvar */
    processo.properties['spu:processo.Despacho'] = despacho
    processo.properties['spu:arquivamento.Status'] = 'workspace://SpacesStore/' + statusArquivamento
    processo.properties['spu:arquivamento.Motivo'] = motivo
    processo.properties['spu:arquivamento.Local'] = local
    processo.properties['spu:arquivamento.Pasta'] = pasta
	processo.save()

    /* Executa o Workflow */
    var workflow = actions.create("start-workflow");
    workflow.parameters.workflowName = "jbpm$spu:arquivarProcesso";
    workflow.execute(processo);
    
	return processo
}

function arquivarProcessos(processosId, despacho, statusArquivamento, motivo, local, pasta) {	
    var processos = new Array()
	var processo
	processosId = eval('(' + processosId + ')');

    for (i=0; i < processosId.length; i++) {
		processo = arquivarProcesso(processosId[i], despacho, statusArquivamento, motivo, local, pasta)
		processos.push(processo)
	}
	return processos
}

function reabrirProcesso(nodeId, despacho) {
	var processo = getNode(nodeId)
    despacho = (!despacho) ? null : despacho    

    /* Executa o Workflow */
    var workflow = actions.create("start-workflow");
    workflow.parameters.workflowName = "jbpm$spu:reabrirProcesso";
    workflow.execute(processo);

    /* Salvar */
    processo.properties['spu:processo.Despacho'] = despacho
	processo.save()
    
	return processo
}

function reabrirProcessos(processosId, despacho) {	
    var processos = new Array()
	var processo
	processosId = eval('(' + processosId + ')');

    for (i=0; i < processosId.length; i++) {
		processo = reabrirProcesso(processosId[i], despacho)
		processos.push(processo)
	}
	return processos
}

function getStatusArquivamento() {
    var status = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Status//cm:Arquivado//*\"');
	return status;
}

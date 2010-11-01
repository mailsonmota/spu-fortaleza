/**
 * Processo
 * 
 * Métodos para gerenciar os Processos do Sistema de Protocolo Único.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 19/10/2010
*/
function getNode(nodeId) {
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	return node;
}

function getPrioridades() {
	var prioridades = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Prioridade//*\"');
	return prioridades;
}

function getProcesso(nodeId) {
	return getNode(nodeId);
}

function getMovimentacoes(nodeId) {
	var processo = getProcesso(nodeId)
	var versoes = processo.getVersionHistory()
	var isMovimentacao
	var de
	var para
	var despacho
	var prazo
	var prioridade
	var dataCriacao
	var movimentacoes = new Array()
	var movimentacao
	for (var i=0; i < versoes.length; i++) {
		isMovimentacao = true
		if (versoes[i+1]) {
			if (versoes[i+1].node.properties['spu:processo.Destino']) {
				de = getNode(versoes[i+1].node.properties['spu:processo.Destino']);
			} else {
				de = ""
			}
		} else {
			if (versoes[i].node.properties['spu:processo.Destino']) {
				de = ""
			} else {
				isMovimentacao = false
			}
		}
	
		if (isMovimentacao) {
			if (versoes[i].node.properties['spu:processo.Destino']) {
				para=getNode(versoes[i].node.properties['spu:processo.Destino'])
			} else {
				para = ""
			}
			if (versoes[i].node.properties['spu:processo.Despacho']) {
				despacho = versoes[i].node.properties['spu:processo.Despacho']
			} else {
				despacho = ""
			}
			if (versoes[i].node.properties['spu:processo.DataPrazo']) {
				prazo = versoes[i].properties['spu:processo.DataPrazo']
			} else {
				prazo = ""
			}
			if (versoes[i].node.properties['spu:processo.Prioridade']) {
				prioridade = versoes[i].node.properties['spu:processo.Prioridade']
			} else {
				prioridade = ""
			}
			data = versoes[i].createdDate

			movimentacao = new Array()
			movimentacao['de'] = de
			movimentacao['para'] = para
			movimentacao['despacho'] = despacho
			movimentacao['prazo'] = prazo
			movimentacao['prioridade'] = prioridade
			movimentacao['data'] = data

			movimentacoes.push(movimentacao)
		}
	}
	return movimentacoes
}

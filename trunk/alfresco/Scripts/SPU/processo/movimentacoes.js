/**
 * Movimentações
 * 
 * Métodos para gerenciar as movimentações do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 16/11/2010
*/
function getMovimentacoes(nodeId) {
	var processo = getProcesso(nodeId)
	var versoes = processo.getVersionHistory()
	var movimentacoes = new Array()
	var movimentacao
	
    for (var i=0; i < versoes.length; i++) {
		isMovimentacao = true
		
		// Se existe uma versão anterior
		if (versoes[i+1]) {
			// Não é movimentação quando o Destino da versão atual é igual ao destino da versão anterior e o despacho também
			if ((versoes[i+1].node.properties['spu:processo.Destino'] == versoes[i].node.properties['spu:processo.Destino']) && 
                (versoes[i+1].node.properties['spu:processo.Despacho'] == versoes[i].node.properties['spu:processo.Despacho'])) {
			    isMovimentacao = false				
			}
		} else {
			// Não é movimentação se a versão atual não tem "Origem" nem "Destino"
			if (!versoes[i].node.properties['spu:processo.Origem'] && !versoes[i].node.properties['spu:processo.Destino']) {
				isMovimentacao = false			
			}
		}

		if (isMovimentacao) {
			de = (versoes[i].node.properties['spu:processo.Origem']) ? getNode(versoes[i].node.properties['spu:processo.Origem']) : ""
			para = (versoes[i].node.properties['spu:processo.Destino']) ? getNode(versoes[i].node.properties['spu:processo.Destino']) : ""
			despacho = (versoes[i].node.properties['spu:processo.Despacho']) ? versoes[i].node.properties['spu:processo.Despacho'] : ""
			prazo = (versoes[i].node.properties['spu:processo.DataPrazo']) ? versoes[i].node.properties['spu:processo.DataPrazo'] : ""
            prioridade = (versoes[i].node.properties['spu:processo.Prioridade']) ? versoes[i].node.properties['spu:processo.Prioridade'] : ""
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

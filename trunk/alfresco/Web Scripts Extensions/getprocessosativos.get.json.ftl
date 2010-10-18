{
<#compress>
	"Processos":[{
		<#list processos as processo>
			"${processo.name}":[{
				"noderef":"${processo.nodeRef}",
				"nome":"${processo.name}", 
				"data":"${processo.properties['spu:Data']?string('dd/MM/yyyy')}",
				"envolvido":"${processo.properties['spu:Manifestante']}",
				"prioridade":"${processo.properties['spu:Prioridade']}",
				"numeroOrigem":"${processo.properties['spu:NumeroOrigem']}", 
				"assunto":[{
					"noderef":"${processo.associations['spu:Assunto'][0].nodeRef}", 
					"nome":"${processo.associations['spu:Assunto'][0].name}"
				}], 
				"tipoProcesso":[{
					"noderef":"${processo.associations['spu:Tipo'][0].nodeRef}", 
					"nome":"${processo.associations['spu:Tipo'][0].name}"
				}]
			}]
			<#if processo_index+1 < processos?size>,</#if>
		</#list>
	}]
</#compress>
}

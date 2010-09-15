{
<#compress>
<#list processos as processo>
	“${processo.name}”:[{
		"noderef":"${processo.nodeRef}",
		"name":"${processo.name}", 
		"properties":[{
			"data":"${processo.properties['spu:Data']?string('dd/MM/yyyy')}",
			"manifestante":"${processo.properties['spu:Manifestante']}",
			"prioridade":"${processo.properties['spu:Prioridade']}",
			"numeroOrigem":"${processo.properties['spu:NumeroOrigem']}"
		}]
	}]
	<#if processo_index+1 < processos?size>,</#if>
</#list>
</#compress>
}

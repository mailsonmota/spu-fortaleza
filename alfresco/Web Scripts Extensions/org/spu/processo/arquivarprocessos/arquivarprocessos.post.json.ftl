{
<#compress>
	"Processos":[{
		<#list processos as processo>
			<#include "../../snippet/snippet_processo.get.json.ftl" />
			<#if processo_index+1 < processos?size>,</#if>
		</#list>
	}]
</#compress>
}

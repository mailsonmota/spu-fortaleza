{
"Protocolos":[{
<#compress>
	<#list chaves as key>
		<#assign protocolo=hierarquias[key][0]>
		"${key}":[{
			<#include "../../snippet/snippet_protocolo.get.json.ftl" />
			,"path": "${key}"
			,"pai": "${hierarquias[key][1]}"
			,"nivel": "${hierarquias[key][2]}"
		}]
		<#if key_index+1 < chaves?size>,</#if>
	</#list>
</#compress>
}]
}

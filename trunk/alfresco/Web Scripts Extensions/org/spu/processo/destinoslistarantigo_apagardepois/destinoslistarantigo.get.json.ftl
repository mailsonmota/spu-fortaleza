{
"Protocolos":[{
<#compress>
	<#list protocolos as protocolo>
		"${protocolo.properties.name}":[{
			<#include "../../snippet/snippet_protocolo.get.json.ftl" />
		}]
		<#if protocolo_index+1 < protocolos?size>,</#if>
	</#list>
</#compress>
}]
}


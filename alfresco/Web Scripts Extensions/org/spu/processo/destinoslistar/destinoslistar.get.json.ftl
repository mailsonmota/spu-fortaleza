{
"Protocolos":[{
<#compress>
	<#list protocolos as protocolo>
		"${protocolo.properties['spu:protocolo.Path']}":[{
			<#include "../../snippet/snippet_protocolo.get.json.ftl" />
		}]
		<#if protocolo_index+1 < protocolos?size>,</#if>
	</#list>
</#compress>
}]
}

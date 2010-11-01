{
"Protocolos":[{
<#compress>
	<#list protocolos as protocolo>
		"${protocolo.properties.name}":[{
			"noderef":"${protocolo.nodeRef}"
			,"nome":"${protocolo.properties.name}"
			<#if protocolo.properties.description != "">
			,"descricao":"${protocolo.properties.description}"
			<#else>
			,"descricao":"${protocolo.properties.name}"
			</#if>
		}]
		<#if protocolo_index+1 < protocolos?size>,</#if>
	</#list>
</#compress>
}]
}

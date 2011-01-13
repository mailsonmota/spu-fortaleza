{
"assuntos":[{
<#compress>
	<#list assuntos as assunto>
		"${assunto.properties.name}":[{
			"noderef":"${assunto.nodeRef}",
			"nome":"${assunto.properties.name}",
			"descricao":"${assunto.properties.title}"
			<#if assunto.parent[0]?exists>			
			,"tipoProcesso":"${assunto.parent.properties.name}"
			<#else>
			,"tipoProcesso":""
			</#if>
		}]
		<#if assunto_index+1 < assuntos?size>,</#if>
	</#list>
</#compress>
}]
}

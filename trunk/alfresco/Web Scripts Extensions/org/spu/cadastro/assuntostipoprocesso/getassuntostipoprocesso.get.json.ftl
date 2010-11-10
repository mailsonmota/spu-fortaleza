{
"assuntos":[
<#compress>
	<#list assuntos as assunto>
		{
			"noderef":"${assunto.nodeRef}",
			"nome":"${assunto.properties.name}", 
			"descricao":"${assunto.properties.description}"
		}<#if assunto_index+1 < assuntos?size>,</#if>
	</#list>
</#compress>
]
}

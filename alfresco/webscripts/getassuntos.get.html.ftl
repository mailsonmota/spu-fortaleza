{
"assuntos":[{
<#compress>
	<#list assuntos as assunto>
		"${assunto.properties.name}":[{
			"noderef":"${assunto.nodeRef}",
			"nome":"${assunto.properties.name}",
			"descricao":"${assunto.properties.description}", 
			"tipoProcesso":"${assunto.assocs['spu:assunto.TipoProcesso'][0].properties.name}"
		}]
		<#if assunto_index+1 < assuntos?size>,</#if>
	</#list>
</#compress>
}]
}

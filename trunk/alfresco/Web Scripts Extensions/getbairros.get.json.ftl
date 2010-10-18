{
"Bairros":[{
<#compress>
<#list bairros as bairro>
	"${bairro.properties.name}":[{
		"noderef": "${bairro.nodeRef}",		
		"nome": "${bairro.properties.name}", 
		"descricao": "${bairro.properties.description}"
	}]
	<#if bairro_index+1 < bairros?size>,</#if>
</#list>
</#compress>
}]
}

<#list opcoes as opcao>
	"${opcao.name}":[{
		"noderef":"${opcao.nodeRef}", 
		"nome":"${opcao.name}",
		<#if opcao.properties.description?exists>
		"descricao":"${opcao.properties.description}"
		<#else>
		"descricao":"${opcao.name}"
		</#if>
	}]
	<#if opcao_index+1 < opcoes?size>,</#if>
</#list>

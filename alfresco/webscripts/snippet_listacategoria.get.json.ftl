<#list opcoes as opcao>
	"${opcao.name}":[{
		"noderef":"${opcao.nodeRef}", 
		"nome":"${opcao.name}"
	}]
	<#if opcao_index+1 < opcoes?size>,</#if>
</#list>

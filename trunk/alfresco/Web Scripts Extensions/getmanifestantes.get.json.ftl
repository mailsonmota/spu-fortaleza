{
"Manifestantes":[{
<#compress>
<#list manifestantes as manifestante>
	"${manifestante['cpf']}":[{
		"cpf":"${manifestante['cpf']}",
		"nome":"${manifestante['nome']}", 
		"bairro":"${manifestante['bairro']}"
	}]<#if manifestante_index+1 < manifestantes?size>,</#if>
</#list>
</#compress>
}]
}

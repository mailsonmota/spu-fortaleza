"${manifestante['cpfCnpj']}":[{
	"cpfCnpj":"${manifestante['cpfCnpj']}"
	,"nome":"${manifestante['nome']}" 
	<#assign opcao = manifestante['bairro']>
	,"bairro":[{<#include "snippet_categoria.get.json.ftl" />}]
}]

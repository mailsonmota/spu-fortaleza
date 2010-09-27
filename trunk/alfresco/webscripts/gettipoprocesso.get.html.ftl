{
<#compress>
"Tipo de Processo":[{
		"${tipoProcesso.name}":[{
			"noderef":"${tipoProcesso.nodeRef}",
			"nome":"${tipoProcesso.name}",
			"titulo":"${tipoProcesso.properties.title}", 
			"simples":"${tipoProcesso.properties['spu:Simples']?string(1, 0)}"
		}]
}]
</#compress>
}

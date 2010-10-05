{
"Assunto":[{
<#compress>
	"${assunto.properties.name}":[{
		"noderef":"${assunto.nodeRef}",
		"nome":"${assunto.properties.name}",
		"descricao":"${assunto.properties.description}", 
		"corpo":"${assunto.properties['spu:assunto.Corpo']}", 
		"notificarNaAbertura":"${assunto.properties['spu:assunto.NotificarNaAbertura']?string(1, 0)}", 
		"tipoProcesso":"${assunto.assocs['spu:assunto.TipoProcesso'][0].properties.name}"
	}]
</#compress>
}]
}

{
<#compress>
"Tipo de Processo":[{
		"${tipoProcesso.name}":[{
			"noderef":"${tipoProcesso.nodeRef}",
			"nome":"${tipoProcesso.name}",
			"titulo":"${tipoProcesso.properties.title}", 
			"letra":"${tipoProcesso.properties['spu:tipoprocesso.Letra']}",
			"tramitacao":"${tipoProcesso.properties['spu:tipoprocesso.Tramitacao']}",
			"abrangencia":"${tipoProcesso.properties['spu:tipoprocesso.Abrangencia']}",
			"observacao":"${tipoProcesso.properties['spu:tipoprocesso.Observacao']}",
			"simples":"${tipoProcesso.properties['spu:tipoprocesso.Simples']?string(1, 0)}", 
			"envolvidoSigiloso":"${tipoProcesso.properties['spu:tipoprocesso.EnvolvidoSigiloso']?string(1, 0)}", 
			"tiposManifestante":[
				<#list tipoProcesso.properties['spu:tipoprocesso.TipoManifestante'] as tipoManifestante>
					"${tipoManifestante}"
					<#if tipoManifestante_index+1 < tipoProcesso.properties['spu:tipoprocesso.TipoManifestante']?size>,</#if>
				</#list>
			]
		}]
}]
</#compress>
}

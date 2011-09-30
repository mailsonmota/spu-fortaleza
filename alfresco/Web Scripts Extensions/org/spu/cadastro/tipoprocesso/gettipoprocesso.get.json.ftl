{
<#compress>
"Tipo de Processo":{
		"${tipoProcesso.name}":{
			"noderef":"${tipoProcesso.nodeRef}"
			,"nome":"${tipoProcesso.name}"
			<#if tipoProcesso.properties.title != "">
			,"titulo":"${tipoProcesso.properties.title}"
			<#else>
			,"titulo":"${tipoProcesso.name}"
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.Letra']?exists>
			,"letra":"${tipoProcesso.properties['spu:tipoprocesso.Letra']}"
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.Tramitacao']?exists>
			,"tramitacao":{
				"noderef":"${tipoProcesso.properties['spu:tipoprocesso.Tramitacao'].nodeRef}"
				,"nome":"${tipoProcesso.properties['spu:tipoprocesso.Tramitacao'].name}"
				<#if tipoProcesso.properties['spu:tipoprocesso.Tramitacao'].properties.description?exists>
				,"descricao":"${tipoProcesso.properties['spu:tipoprocesso.Tramitacao'].properties.description}"
				<#else>
				,"descricao":"${tipoProcesso.properties['spu:tipoprocesso.Tramitacao'].name}"
				</#if>
			}
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.Abrangencia']?exists>
			,"abrangencia":{
				"noderef":"${tipoProcesso.properties['spu:tipoprocesso.Abrangencia'].nodeRef}"
				,"nome":"${tipoProcesso.properties['spu:tipoprocesso.Abrangencia'].name}"
				<#if tipoProcesso.properties['spu:tipoprocesso.Abrangencia'].properties.description?exists>
				,"descricao":"${tipoProcesso.properties['spu:tipoprocesso.Abrangencia'].properties.description}"
				<#else>
				,"descricao":"${tipoProcesso.properties['spu:tipoprocesso.Abrangencia'].name}"
				</#if>
			}
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.Observacao']?exists>
			,"observacao":"${tipoProcesso.properties['spu:tipoprocesso.Observacao']}"
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.Simples']?exists>
			,"simples":"${tipoProcesso.properties['spu:tipoprocesso.Simples']?string(1, 0)}"
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.EnvolvidoSigiloso']?exists>
			,"envolvidoSigiloso":"${tipoProcesso.properties['spu:tipoprocesso.EnvolvidoSigiloso']?string(1, 0)}"
			</#if>
			<#if tipoProcesso.properties['spu:tipoprocesso.TipoManifestante']?exists>
			,"tiposManifestante":[{
				<#list tipoProcesso.properties['spu:tipoprocesso.TipoManifestante'] as tipoManifestante>
					"${tipoManifestante.name}":{
						"noderef":"${tipoManifestante.nodeRef}"
						,"nome":"${tipoManifestante.name}"
						<#if tipoManifestante.properties.description?exists>
						,"descricao":"${tipoManifestante.properties.description}"
						<#else>
						,"descricao":"${tipoManifestante.name}"
						</#if>
					}
					<#if tipoManifestante_index+1 < tipoProcesso.properties['spu:tipoprocesso.TipoManifestante']?size>,</#if>
				</#list>
			}]
			<#else>
			,"tiposManifestante":[{}]
			</#if>
			<#if tipoProcesso.assocs['spu:tipoprocesso.Protocolos']?exists>
			,"protocolos":[{
				<#list tipoProcesso.assocs['spu:tipoprocesso.Protocolos'] as protocolo>
					"${protocolo.properties.name}":[{
					<#include "../../snippet/snippet_protocolo.get.json.ftl" />
					}]
					<#if protocolo_index+1 < tipoProcesso.assocs['spu:tipoprocesso.Protocolos']?size>,</#if>
				</#list>
			}]
			<#else>
			,"protocolos":[{}]			
			</#if>
		}
}
</#compress>
}

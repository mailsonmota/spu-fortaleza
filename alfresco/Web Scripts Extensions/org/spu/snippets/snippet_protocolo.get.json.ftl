<#if protocolo != "">
"noderef":"${protocolo.nodeRef}"
,"nome":"${protocolo.properties.name}"
	<#if protocolo.properties.title != "">
,"descricao":"${protocolo.properties.title}"
	<#else>
,"descricao":"${protocolo.properties.name}"
	</#if>
<#else>
"noderef":""
,"nome":""
,"descricao":""
</#if>

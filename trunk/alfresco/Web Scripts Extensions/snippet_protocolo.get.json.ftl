<#if protocolo != "">
"noderef":"${protocolo.nodeRef}"
,"nome":"${protocolo.properties.name}"
	<#if protocolo.properties.description != "">
,"descricao":"${protocolo.properties.description}"
	<#else>
,"descricao":"${protocolo.properties.name}"
	</#if>
<#else>
"noderef":""
,"nome":""
,"descricao":""
</#if>

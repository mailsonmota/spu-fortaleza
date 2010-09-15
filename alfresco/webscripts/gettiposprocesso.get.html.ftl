{
<#compress>
<#list tiposProcesso as rootnode>
“${rootnode.name}”:[
{
"noderef":"${rootnode.nodeRef}",
"name":"${rootnode.name}"
}
]
</#list>
</#compress>
}

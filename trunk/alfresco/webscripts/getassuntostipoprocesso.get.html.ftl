{
<#compress>
<#list companyhome.childrenByLuceneSearch[luceneSearch] as child>
<#list classification.getRootCategories("cm:generalclassifiable") as rootnode>
<#if rootnode.name = "Assuntos">
<#list rootnode.immediateSubCategories  as all>
<#if all.nodeRef = child.nodeRef>
“${all.properties.name}”:[
<#list all.subCategories?sort_by("name") as mylist>
{
"noderef":"${mylist.nodeRef}",
"name":"${mylist.properties.name}"
}<#if mylist_index+1 &lt; all.subCategories?size>,</#if>
</#list>]<#if all_index+1 &lt; rootnode.immediateSubCategories?size>,</#if>
</#if>
</#list>
</#if>
</#list>
</#list>
</#compress>
}

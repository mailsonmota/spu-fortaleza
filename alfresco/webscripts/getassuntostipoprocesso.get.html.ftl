{
"assuntos":[{
<#compress>
	<#list companyhome.childrenByLuceneSearch[luceneSearch] as child>
		<#list classification.getRootCategories("cm:generalclassifiable") as rootnode>
			<#if rootnode.name = "Assuntos">
				<#list rootnode.immediateSubCategories as all>
					<#if all.nodeRef = child.nodeRef>
						"${all.properties.name}":[
							<#list all.subCategories?sort_by("name") as mylist>
							{
								"noderef":"${mylist.nodeRef}",
								"nome":"${mylist.properties.name}", 
								"descricao":"${mylist.properties.description}"
							}
							<#if mylist_index+1 < all.subCategories?size>,</#if>
							</#list>
						]<#if all_index+2 < rootnode.immediateSubCategories?size>,</#if>
					</#if>
				</#list>
			</#if>
		</#list>
	</#list>
</#compress>
}]
}

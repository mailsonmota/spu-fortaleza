{
	"groups":[<#list groups as group>
		{
			"name":"${group.properties.authorityName}"
		}<#if group_index+1 &lt; groups?size>,</#if>
		</#list>
	]
}
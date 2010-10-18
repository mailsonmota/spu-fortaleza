{
	"groups":[<#list resultado as item>
		{
			"item":"${item}"
		}<#if item_index+1 &lt; resultado?size>,</#if>
		</#list>
	]
}
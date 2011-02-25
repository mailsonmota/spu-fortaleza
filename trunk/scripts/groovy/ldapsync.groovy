@Grapes(
    @Grab(group='org.codehaus', module='yagll', version='1.0')
)
import org.codehaus.yagll.Yagll
import javax.naming.directory.SearchControls
import org.codehaus.yagll.context.LdapUtils

def setoresProtocolo = [
3:932,5:845,6:853,8:162,10:1122,12:262,14:826,15:676,16:398,17:767,18:1539,19:538,20:802,22:561,24:214,
25:778,28:215,29:251,30:124,33:703,105:108,114:118,473:493,633:635,839:841,868:876,955:1009,1018:1048,
1052:1323,1076:1081,1178:1179,1242:1261,1287:1300,1415:1424,1461:1463,1507:1699
]

def resgatarEstrutura(setoresProtocolo){
	Yagll.config = new ConfigObject()
	Yagll.config.setProperty('url', "ldap://172.30.116.21/")
	Yagll.config.setProperty('dn', "cn=admin,dc=intranet,dc=cti,dc=fortaleza,dc=ce,dc=gov,dc=br")
	Yagll.config.setProperty('password', "123cti")
	/*Yagll.config.setProperty('dn', "uid=thiago.pinheiro,dc=CTI,dc=rh,dc=intranet,dc=cti,dc=fortaleza,dc=ce,dc=gov,dc=br")
	Yagll.config.setProperty('password', "123")*/
	
	def searchControls = new SearchControls()
	    searchControls.setSearchScope(SearchControls.SUBTREE_SCOPE)
	def result = LdapUtils.search("dc=adm,dc=diretorio,dc=intranet,dc=cti,dc=fortaleza,dc=ce,dc=gov,dc=br",
		"(|(objectClass=unidadeGestao)(objectClass=unidadeAdminstrativa))", searchControls)
	result.collect {
		def aux = it['='][0].replace(',dc=adm,dc=diretorio,dc=intranet,dc=cti,dc=fortaleza,dc=ce,dc=gov,dc=br','').split(/,*\w+=/)
		def path = aux.reverse().join('/')
		def parent = (aux.size()>2)?aux.reverse()[0..-3].join('/'):''
		def idProtocolo = (it['idEstruturaSPU'])?(it['idEstruturaSPU'][0] as Integer):null
		def receptorId = (idProtocolo)?setoresProtocolo[idProtocolo]:null
		
		def receptor = (receptorId)?LdapUtils.search("dc=adm,dc=diretorio,dc=intranet,dc=cti,dc=fortaleza,dc=ce,dc=gov,dc=br",
					"(idEstruturaSPU=${receptorId})", searchControls):null
		def receptorPath = (receptor)?(receptor['='][0][0].replace(',dc=adm,dc=diretorio,dc=intranet,dc=cti,dc=fortaleza,dc=ce,dc=gov,dc=br','').split(/,*\w+=/).reverse().join('/')):''
		def map = ['id':(it['idEstruturaSPU'])?it['idEstruturaSPU'][0]:'',
		'name': aux[1].replaceAll('/',' - '),
		'path':path,
		'parent':parent,
		'desc':(it['description'])?it['description'][0]:'',
		'obj':(it['objectClass'])?it['objectClass'][0]:'',
		'grupo':(it['grupoSPU'])?it['grupoSPU'][0]:'',
		'recebePor':receptorPath]
		}.sort({it['path']})
}

def criarJson(setores) {
	def result = "{\"estrutura\":["
	resgatarEstrutura(setores).eachWithIndex{ obj, i -> 
		result += "\t"+((i)?', ':'')
		result += "{\"id\":\"${obj.id}\", \"name\":\"${obj.name}\", \"path\":\"${obj.path}\", \"parent\":\"${obj.parent}\", \"desc\":\"${obj.desc}\", \"obj\":\"${obj.obj}\", \"grupo\":\"${obj.grupo}\""
		result += ", \"recebe\":\"${obj.recebePor}\""
		result += "}\n"
	}
	result += "]}"
	return result
}

println criarJson(setoresProtocolo)
//println resgatarEstrutura(setoresProtocolo)

//'curl -v -u admin:admin -X POST -d @estrutura.json -H "Content-Type:application/json" "http://172.30.116.21:8080/alfresco/service/spu/gerar/protocolos"'.execute()

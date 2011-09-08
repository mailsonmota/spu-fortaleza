@Grapes([
     @Grab(group='org.codehaus.groovy.modules.http-builder', module='http-builder', version='0.5.1' )
])
import groovy.sql.Sql
import groovy.xml.MarkupBuilder
import java.text.Normalizer
import groovyx.net.http.HTTPBuilder
import static groovyx.net.http.ContentType.JSON

def tiposSPU = [1:['TEXTO','xs:string'], //multiple
				2:['NÚMERO','xs:integer'],
				3:['OPÇÃO','xs:string'], //multiple
				5:['DATA','xs:date'],
				6:['PARAGRAFO','xs:string'],
				7:['VALOR','xs:float'], 
				11:['CPF/CNPJ','xs:string'],
				12:['CHECK','xs:string'], //multiple
				13:['ORGAO','xs:string'], //multiple
				15:['BAIRRO','xs:string'],
				16:['LOGRADOURO','xs:string']]

def generateAll(sql, tipos, clback) {
	def queryAssuntosComForm = """
				select tpa.tpa_codigo, 
					   tpa.tpa_nome, 
					   tpt.tpt_nome, 
					   tpt.tpt_letra, 
					   tpt.tpt_codigo 
				from tab_processo_tipo tpt
					 left join tab_tipo_assunto tta on tpt.tpt_codigo = tta.tta_tpt_codigo
					 left join tab_processo_assunto tpa on tpa.tpa_codigo = tta.tta_tpa_codigo
					 inner join tab_assunto_documento tad on (tad.tad_tpa_codigo = tpa.tpa_codigo)
					 inner join tab_documento td on (tad.tad_tdo_codigo = td.tdo_codigo)
				where tpa.tpa_codigo is not null
				group by tpa.tpa_codigo, 
						 tpa.tpa_nome, 
						 tpt.tpt_nome, 
						 tpt.tpt_letra, 
						 tpt.tpt_codigo having count(*) > 0
				order by tpt.tpt_nome asc
			    """
	def tipoEnvolvido = [1:'Pessoa Juridica (Sem Ser Orgao)',
						 2:'Pessoa Fisica (Sem Ser Servidor)',
						 3:'Servidor Efetivo',
						 4:'Servidor Comissionado',
						 6:'Outros (Estagiario. Terceirizado)',
						 7:'Órgão da PMF']
	def abrangencia = [1:'Interna', 2:'Externa']
	def tramitacao = [1:'Série', 2:'Paralelo']
	
	def queryEnvolvidos = """
				select tg.tge_codigo, 
					   tg.tge_nome 
				from tab_tipo_pessoa ttp 
				inner join tab_geral tg on ttp.tpe_codigo = tg.tge_codigo 
										   and tg.tge_tipo = 203 
										   and ttp.tpe_tpt_codigo = ?
				"""
	def queryTramitacaoAbrangencia = """
				select tpt_tramitacao, 
					   tpt_abrangencia 
				from tab_processo_tipo 
				where tpt_codigo = ?
				"""
	
	def assuntosComForm = []
	sql.eachRow(queryAssuntosComForm, { it ->
		assuntosComForm << it[0]
		def aux = normalize(it[1]).split(' ').findAll{it.size() > 1}.collect{it[0].toUpperCase() + it[1..it.size()-1]}
		def form = [ns:"spu:${camelize(it[1])}", name:"form${camelize(it[1],false)}", head:aux[0], formHead:aux.join(' '), fields:[]]
		def xml = generateAssunto(sql, it[0], form, tipos)
		def envolvidos = sql.rows(queryEnvolvidos, [it[4]]).collect{ e -> tipoEnvolvido.get(e[0] as Integer)}
		def tramitAbran = sql.rows(queryTramitacaoAbrangencia, [it[4]])[0]
		clback(xml, "form${camelize(it[1],false)}", finalName(it[1]), finalName(it[2]), (it[3])?it[3]:'', envolvidos, tramitacao[tramitAbran[0] as Integer], abrangencia[tramitAbran[1] as Integer])
	});
	
	def queryAssuntosSemForm = """
				select tpa.tpa_codigo, 
					   tpa.tpa_nome, 
					   tpt.tpt_nome, 
					   tpt.tpt_letra, 
					   tpt.tpt_codigo 
				from tab_processo_tipo tpt
					 left join tab_tipo_assunto tta on tpt.tpt_codigo = tta.tta_tpt_codigo
					 left join tab_processo_assunto tpa on tpa.tpa_codigo = tta.tta_tpa_codigo
				where tpa.tpa_codigo is not null 
					  and tpa.tpa_codigo not in (""" + assuntosComForm.join(',') + """)
				group by tpa.tpa_codigo, 
						 tpa.tpa_nome, 
						 tpt.tpt_nome, 
						 tpt.tpt_letra, 
						 tpt.tpt_codigo having count(*) > 0
				order by tpt.tpt_nome asc
			    """
	sql.eachRow(queryAssuntosSemForm, { it ->
		def envolvidos = sql.rows(queryEnvolvidos, [it[4]]).collect{ e -> tipoEnvolvido.get(e[0] as Integer)}
		def tramitAbran = sql.rows(queryTramitacaoAbrangencia, [it[4]])[0]
		clback('', '', finalName(it[1]), finalName(it[2]), (it[3])?it[3]:'', envolvidos, tramitacao[tramitAbran[0] as Integer], abrangencia[tramitAbran[1] as Integer])
	});
}
                     
def generateAssunto(sql, assuntoId, formDef, tipos) {
	def queryForm = """
				select td.tdo_codigo, 
					   td.tdo_nome, 
					   td.tdo_ordem, 
					   td.tdo_tipo, 
					   tdd.tdd_nome, 
					   tad.tad_requerido
				from tab_processo_assunto tpa 
					 inner join tab_assunto_documento tad on (tad.tad_tpa_codigo = tpa.tpa_codigo)
					 inner join tab_documento td on (tad.tad_tdo_codigo = td.tdo_codigo)
					 left join tab_documento_detalhe tdd on (tdd.tdd_tdo_codigo = td.tdo_codigo)
				where tpa.tpa_codigo = ?
				order by td.tdo_ordem, 
						 td.tdo_nome
				"""
	def map = [:]
	sql.eachRow(queryForm, [assuntoId], {
		def type = tipos[it[3].toInteger()][1]
		def key = [it[1], camelize(it[1]), type]
		if (!it[5]/*requirido*/) {
			key << 0 
		} else{
			key << -1
		}
		if (!map.get(key)) {
			map.put(key,[])
		}
		if (it[4]) {
			map.get(key) << it[4]
		}
	})
	
	def aux = map.keySet().collect{it << map[it]}
	formDef.put('fields',aux)
	generateForm(formDef)
}                     

def normalize(input) {
	 Normalizer.normalize(input, Normalizer.Form.NFD).replaceAll("[^\\p{ASCII}]", "").replaceAll(/\W/, ' ').toLowerCase()
}

def finalName(input) {
	input = input.replace('-','')
	if(input.endsWith('.')){ input = input[0..input.length()-2]}
	input.toLowerCase().split(' ').findAll{it.size() > 1}.collect{ it.capitalize() }.join(' ')//.replaceAll('/','-').replaceAll(':','-')
}

def camelize(input, firstLow = true) {
	def cam = normalize(input).split(' ').findAll{it.size() > 1}.collect{ it.capitalize() }.join('')
	(firstLow)?cam[0].toLowerCase() + cam[1..cam.size()-1]:cam
}

def generateField(xml, label, tname, ttype='xs:string', tminOccurs=-1, restrictions=[]) {
	def exceptions = ['responsavelMembro','valorEstimado']
	if (exceptions.contains(tname)) restrictions.clear()//'ajuste' de bios usuarios/programadores do spu
	def aux = [name:tname]
	if (!restrictions) aux.put('type',ttype)
	if (tminOccurs >=0) aux.put('minOccurs', tminOccurs)

	xml.'xs:element'(aux) {
		xml.'xs:annotation'() {
			xml.'xs:appinfo'() {
				xml.'xhtml:label'(label)
			}
		}
		if (restrictions) {
			xml.'xs:simpleType'() {
				xml.'xs:restriction'(base:'xs:string') {
					for(res in restrictions){
						xml.'xs:enumeration'(value:res)
					}
				}
			}
		}
	}
}

def generateXml(xml, namespace, tname, head, formHead, fields) {
	xml.'xs:schema'('xmlns:xs':'http://www.w3.org/2001/XMLSchema', 'xmlns:xhtml':'http://www.w3.org/1999/xhtml', targetNamespace:namespace, elementFormDefault:"qualified"){
		generateField(xml, head, tname, tname)
		xml.'xs:complexType'(name:tname) {
			xml.'xs:sequence'() {
				xml.'xs:element'(name:'identificacao') {
					xml.'xs:annotation'() {
						xml.'xs:appinfo'() {
							xml.'xhtml:label'(formHead)
						}
					}
					xml.'xs:complexType'() {
						xml.'xs:sequence'() {
							for(f in fields) {
								f.add(0, xml)
								generateField(f)
							}
						}
					}
				}
			}
		}
	}
}

def generateForm(form) {
	def writer = new StringWriter()
	def xml = new MarkupBuilder(writer)
	generateXml(xml, form.ns, form.name, form.head, form.formHead, form.fields)
	writer.toString() 
}

def openSqlConnection() {
	return Sql.newInstance("jdbc:oracle:thin:@//172.17.1.1/pmf", "SIGAP",
                     		"qweflocgrc", "oracle.jdbc.driver.OracleDriver")
}

def createHttpSession() {
	def http = new HTTPBuilder('http://localhost:8080/alfresco/');
	http.auth.basic 'admin', 'admin'
	/*def http = new HTTPBuilder('http://172.30.117.73:8080/alfresco/');
	http.auth.basic 'admin', 'Naiany'*/
	return http
}

def alfrescoServiceForms(http, tprocesso, let, assun, form, cont, envs, tram, abran) {
	def postArgs = [tipoProcesso:tprocesso.toString(), letra:let.toString(), assunto:assun.toString(), formName:((String)form+".xsd"), conteudo:cont.toString(), envolvidos:envs, tramitacao:tram.toString(), abrangencia:abran.toString()]
	print "${postArgs.tipoProcesso}, ${postArgs.letra}, ${postArgs.assunto}, ${postArgs.formName}, "
	try {
		http.post( path: 'service/spu/gerar/tiposprocesso', requestContentType: JSON, body: postArgs ) { resp, reader ->
			println reader
		}
	} catch(groovyx.net.http.HttpResponseException ex) {
		ex.response.entity.writeTo System.out
	}
}

//Inicio da Execução
def sql = openSqlConnection()
def http = createHttpSession()
generateAll(sql, tiposSPU, {a, b, c, d, e, f, g, h -> alfrescoServiceForms(http, d,e,c,b,a, f, g, h)})

//generateAll(sql, tiposSPU, {a, b, c, d, e -> def root = "xsds/"+d.replaceAll('/','-')+"/"+c.replaceAll('/','-'); println root+"/"+b+".xsd"; new File(root).mkdirs(); new File(root+"/"+b+".xsd").setText(a, 'UTF-8')})
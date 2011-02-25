@Grapes([
     @Grab(group='org.codehaus.groovy.modules.http-builder', module='http-builder', version='0.5.1' )
])
import groovy.sql.Sql
import groovy.xml.MarkupBuilder
import java.text.Normalizer
import groovyx.net.http.HTTPBuilder
import static groovyx.net.http.ContentType.JSON

def generateAll(sql, callback) {
    def queryProtocolos = """
                select 
                    tlo_codigo, 
                    tlo_sigla, 
                    tlo_descricao, 
                    tla_lot_protocolo, 
                    SYS_CONNECT_BY_PATH(tlo_sigla, '\') path
                from 
                    tab_lotacao 
                    left join tab_lotacao_aux on tlo_codigo = tla_tlo_codigo
                start with tlo_codigo = 1
                connect by prior tlo_codigo = tlo_hierarquia and tlo_codigo <> 1
                order by level asc
                """
    def protocolos = []
    def hierarquia = 0
    def path = ''
    sql.eachRow(queryAssuntosComForm, { it ->
        def i = it[0]
        if (hierarquia != it[0]) {
            path = 
        }
        
    });
}


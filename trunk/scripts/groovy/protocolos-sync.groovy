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
                select tpa.tpa_codigo, tpa.tpa_nome, tpt.tpt_nome, tpt.tpt_letra, tpt.tpt_codigo from tab_processo_tipo tpt
                left join tab_tipo_assunto tta on tpt.tpt_codigo = tta.tta_tpt_codigo
                left join tab_processo_assunto tpa on tpa.tpa_codigo = tta.tta_tpa_codigo
                inner join tab_assunto_documento tad on (tad.tad_tpa_codigo = tpa.tpa_codigo)
                inner join tab_documento td on (tad.tad_tdo_codigo = td.tdo_codigo)
                where tpa.tpa_codigo is not null
                group by tpa.tpa_codigo, tpa.tpa_nome, tpt.tpt_nome, tpt.tpt_letra, tpt.tpt_codigo having count(*) > 0
                order by tpt.tpt_nome asc
                """
}
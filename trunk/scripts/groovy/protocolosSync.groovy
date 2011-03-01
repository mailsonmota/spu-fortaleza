import groovy.sql.Sql
import java.text.Normalizer

def sql = openSqlConnection()
toJson([estrutura: getProtocolos(sql)])

def openSqlConnection() {
    return Sql.newInstance("jdbc:oracle:thin:@//172.17.1.1/pmf", "SIGAP",
                     "qweflocgrc", "oracle.jdbc.driver.OracleDriver")
}

def toJson(map){
    println '{"estrutura":['
    map['estrutura'].eachWithIndex{ it, i -> 
        print ((i > 0) ? ',{' : '{')
        it.eachWithIndex{ m, j ->
            print ((j > 0) ? ',' : '')
            print "\"${m.key}\":\"${m.value}\"" 
        }
        println '}'
    }
    println ']}'
}

def getProtocolos(sql) {
    def queryProtocolos = """
            select tlo_codigo, 
                   tlo_sigla, 
                   tlo_descricao, 
                   tla_lot_protocolo, 
                   SYS_CONNECT_BY_PATH(tlo_sigla, '\\') path, 
                   pathprotocolo
            from tab_lotacao 
                 left join tab_lotacao_aux on tlo_codigo = tla_tlo_codigo
                 left join (
                    select tlo_codigo as codigopai, 
                           SYS_CONNECT_BY_PATH(tlo_sigla, '\\') pathprotocolo 
                    from tab_lotacao 
                    start with tlo_codigo = 1 
                    connect by prior tlo_codigo = tlo_hierarquia and tlo_codigo <> 1
                 ) on tla_lot_protocolo = codigopai
            start with tlo_codigo = 1
            connect by prior tlo_codigo = tlo_hierarquia and tlo_codigo <> 1
            order by path asc
            """
    def protocolos = []
    sql.eachRow(queryProtocolos, { it ->
        def protocolo = [name:(normalize(it.tlo_sigla).toUpperCase()), 
                         desc:it.tlo_descricao, 
                         grupo:(getNomeGrupoFromPath(getPathAjustado(it.path))), 
                         path:(getPathAjustado(it.path)), 
                         recebe:(getPathAjustado(it.pathprotocolo)), 
                         parent:(getParentFromPath(getPathAjustado(it.path)))]
        protocolos << protocolo
    });
    return protocolos
}

def normalize(input){
     input = removerAcentuacao(input).replaceAll("[^\\p{ASCII}]", "").replaceAll(/\W+/, '-')
     removerSeparadorInicioFim(input)
}

def removerAcentuacao(input) {
    Normalizer.normalize(input, Normalizer.Form.NFD)
}

def removerSeparadorInicioFim(input) {
    input = (input[0] == '-') ? input[1..-1] : input
    input = (input[-1] == '-') ? input[0..-2] : input
}

def getNomeGrupoFromPath(path) {
    path.replaceAll('/', '_')
}

def getPathAjustado(path) {
    if (!path) {
        return ''
    }
    path = path[1..-1].replaceAll(/${'\\\\'}/, '#')
    path = Normalizer.normalize(path, Normalizer.Form.NFD).replaceAll("[^\\p{ASCII}#]", "")
    path = path.replaceAll(/[\/\s()-\.]+/, '-').replaceAll(/#-/, '#').replaceAll(/-#/, '#')
    path = (path[0] == '-') ? path[1..-1] : path
    path = (path[-1] == '-') ? path[0..-2] : path
    path = path.replaceAll('#', '/').toUpperCase()
}

def getParentFromPath(path) {
    def parent = (path =~ ".*([A-Z]+/)")
    return (parent) ? parent[0][0][0..-2] : ''
}

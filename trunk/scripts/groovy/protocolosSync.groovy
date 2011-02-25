import groovy.sql.Sql

def gerarProtocolos(sql) {
    def queryProtocolos = """
                select 
                    tlo_codigo, 
                    tlo_sigla, 
                    tlo_descricao, 
                    tla_lot_protocolo, 
                    SYS_CONNECT_BY_PATH(tlo_sigla, '\\') path, 
                    pathprotocolo
                from 
                    tab_lotacao 
                    left join tab_lotacao_aux on tlo_codigo = tla_tlo_codigo
                    left join (
                        select 
                            tlo_codigo as codigopai, 
                            SYS_CONNECT_BY_PATH(tlo_sigla, '\\') pathprotocolo 
                        from 
                            tab_lotacao 
                        start with tlo_codigo = 1 
                        connect by prior tlo_codigo = tlo_hierarquia and tlo_codigo <> 1
                    ) on tla_lot_protocolo = codigopai
                start with tlo_codigo = 1
                connect by prior tlo_codigo = tlo_hierarquia and tlo_codigo <> 1
                order by path asc
                """
    def protocolos = []
    sql.eachRow(queryProtocolos, { it ->
        def protocolo = [name:it.tlo_sigla, 
                         title:it.tlo_sigla, 
                         description:it.tlo_descricao, 
                         path:it.path, 
                         setorProtocolo:it.pathprotocolo]
        protocolos << protocolo
    });
}

def getPathAjustado(path) {
    return path[1..-1]//.replaceAll('\', '/')
}

def openSqlConnection(){
    return Sql.newInstance("jdbc:oracle:thin:@//172.17.1.1/pmf", "SIGAP",
                     "qweflocgrc", "oracle.jdbc.driver.OracleDriver")
}

def sql = openSqlConnection()
gerarProtocolos(sql)
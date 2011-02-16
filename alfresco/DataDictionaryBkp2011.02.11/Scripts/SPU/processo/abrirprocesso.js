/**
 * Abertura de Processo
 * 
 * Métodos para gerenciar a abertura de processos do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 29/11/2010
*/

/* Função zeroFill
   Por: Peter Bailey <http://stackoverflow.com/users/8815/peter-bailey>
   Fonte: http://stackoverflow.com/questions/1267283/how-can-i-create-a-zerofilled-value-using-javascript
 */
function zeroFill(number, width) {
    width -= number.toString().length;
    if (width > 0) {
        return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
    }
    return number;
}

function gerarNumeroProcesso(sigla) {
    var dia = zeroFill(new Date().getDate(), 2);
    var mes = zeroFill(new Date().getMonth() + 1, 2)
    var hora = zeroFill(new Date().getHours(), 2)
    var minuto = zeroFill(new Date().getMinutes(), 2)
    var segundo = zeroFill(new Date().getSeconds(), 2)
    var ano = new Date().getFullYear()

    return sigla + dia + mes + hora + minuto + segundo + "/" + ano;
}

function abrirProcesso() {}

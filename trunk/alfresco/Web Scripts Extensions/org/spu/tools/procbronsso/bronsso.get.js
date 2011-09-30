var processos = search.luceneSearch('TYPE:"spu:Processo"')

var debug = ''

for each (processo in processos) {
    //debug += processo.qnamePath + " ______________ "
    debug += processo.qnamePath + ' :: ' + processo.remove() + ', '
}

model.resultado = debug

/*var grupos = groups.searchGroups('PMF_*');
var grande_string = ''

while (grupos.length > 0) {
    for (var i = 0; i < grupos.length; i++) {
        grande_string += grupos[i].getShortName() + ', '
        grupos[i].deleteGroup()
    }
    var grupos = groups.searchGroups('PMF_*');
}
model.resultado = grande_string*/

/*var grupo_spu_raiz = groups.getGroup('SPU_PROTOCOLOS');

var grupos_vetor = new Array()
grupos_vetor = grupo_spu_raiz.getAllGroups()

var grupos_string = grupo_spu_raiz.getFullName() + ' -- '
for each (grupo in grupos_vetor) {
    grupos_string += grupo.getFullName() + ' -- ';
    grupo.deleteGroup();
}

//grupo_spu_raiz.deleteGroup()

throw grupos_string + '. qtd: ' + grupos_vetor.length*/

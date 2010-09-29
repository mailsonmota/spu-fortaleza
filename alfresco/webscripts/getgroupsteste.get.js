var grupos = people.getContainerGroups(person);
model.grupos = grupos;

//

function getNoderefName() {}

function getParents(objeto) {
    var resultado = objeto.name //getNoderefName()
	if (objeto.parent == null) {
	    return resultado;
	} else {
	    resultado += ", " + getParents(objeto.parent);
	    return resultado;
	}
}

function getParents2(objeto) {
    var vetor = new Array();
    vetor.push(objeto.name);
    if (objeto.parent == null) {
	return vetor;
    } else {
	//for (var num in objeto.parent) {
	    vetor.push(getParents2(objeto.parent));
	//}
	return vetor;
    }
}

var resultadosGetParents = new Array();
for (var num in grupos) {
    resultadosGetParents[num] = getParents(grupos[num]);
}
model.resultadoGetParents = resultadosGetParents;

var resultadosGetParents2 = new Array();
for (var num in grupos) {
	resultadosGetParents2[num] = getParents2(grupos[num]);
}
model.resultadoGetParents2 = resultadosGetParents2;

model.resultadoGetParents3 = search.luceneSearch('ID:"' + grupos[2].parent.nodeRef + '"');

//function addGrupos(no) {
//	var meuVetor = new Array();
//	if (no.children) {
//		for (var num in no.children) {
//			meuVetor[no.properties.authorityName] = addGrupos(no.children[num]);
//		}
//	} else {
//		meuVetor[no.properties.authorityName] = no.properties.authorityName;
//	}
//	return meuVetor;
//}
//
//var grupoResultados = new Array();
//model.gruposResultados = addGrupos(grupos[0]) // retorno do getPMFIndex

var num_index;

/* recebe objeto grupos */
function getTop(objeto) {
    var listaCompleta = new Array();
    for (var i in objeto) {
	listaCompleta[i] = i;
    }
    for (var i in objeto) {
	var children = new Array();
	if (objeto[i].children.length > 0) {
	    //cada children do objeto num
	    //cada children do objet
	    for (var j in objeto[i]) {}
	}
    }
}

//

function getRaiz(grupos) {
    maiorQtdFilhos = 0;
    raiz = null;
    for (var i in grupos) {
	qtdFilhos = calcularQtdFilhos(grupos[i]); 
	if (qtdFilhos >= maiorQtdFilhos) {
	    maiorQtdFilhos = qtdFilhos;
	    raiz = grupos[i];
	}
    }
    return raiz;
}

function calcularQtdFilhos(no) {
    qtd = 0;
    if (no.children.length > 0) {
	for (var i in no.children) {
	    qtd += calcularQtdFilhos(no.children[i]);
	}
    }
    return qtd;
}

model.resultado123 = getRaiz(grupos);

/*
grupos[0] = 'PMF';
grupos[0][0] = 'SAM';
grupos[0][0][0] = 'SET1';
grupos[0][0][0][0] = 'DEP1';
grupos[0][1] = 'SME';
*/

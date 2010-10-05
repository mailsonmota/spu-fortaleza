var grupos = people.getContainerGroups(person);

function getRaiz(grupos) {
  maiorQtdFilhos = 0;
  raiz = null;
  for (var i in grupos) {
	  qtdFilhos = calcularQtdFilhos(grupos[i]); 
	  if (qtdFilhos > maiorQtdFilhos) {
	    maiorQtdFilhos = qtdFilhos;
	    raiz = grupos[i];
	  }
  }
  return raiz;
}

function calcularQtdFilhos(no) {
  var qtd = 0;
  if (no.children.length > 0) {
  	for (var num in no.children) {
	    qtd += calcularQtdFilhos(no.children[num]) + 1;
	  }
  }
  return qtd;
}

function estaContidoMeusGrupos(no) {
  var grupos = people.getContainerGroups(person);
  for (var i = 0; i < grupos.length; i++) {
	  if (no.properties.authorityName == grupos[i].properties.authorityName) {
	    return 1;
	  }
  }
  return 0;
}

function pegarFilhos(no) {
    var vetor = new Array()
	  if (no.children.length > 0) {
	    vetor[0] = no.properties.authorityName
	    var j = 1
	    for (var i = 0; i < no.children.length; i++) {
	      if (estaContidoMeusGrupos(no.children[i])) {
		      vetor[j++] = pegarFilhos(no.children[i])
		    }
	    }
	    
	  } else {
	    vetor[0] = no.properties.authorityName
	  }
	  return vetor
}

var raiz = getRaiz(grupos)

var filhos = pegarFilhos(raiz)

function resultado() {
  this.vetor = new Array()
  this.vetor_string = new Array()
}

var resultado = new resultado()

function adiciona_vetor_resultado(item, nivel, resultado) {
  for (var i = 0; i < resultado.vetor.length + 1; i++) {
    if ((resultado.vetor[i] == undefined || resultado.vetor[i][nivel] == undefined) && resultado.vetor[i+1] == undefined) {
      if (resultado.vetor[i] == undefined) {
        resultado.vetor[i] = new Array()
      }
      resultado.vetor[i].push(item)
      /* preenche o resto do vetor, para trás */
      if (resultado.vetor[i][nivel] == undefined) {
        for (var j = nivel - 1; j >= 0; j--) {
          resultado.vetor[i].unshift(resultado.vetor[i-1][j])
        }
      }
      return
    }
  }
}

function entra_vetor(vetor, nivel, resultado) {
  adiciona_vetor_resultado(vetor[0], nivel, resultado)
  nivel++
  for (var i = 1; i < vetor.length; i++) {
    entra_vetor(vetor[i], nivel, resultado)
  }
}

entra_vetor(filhos, 0, resultado)

for (var i = 0; i < resultado.vetor.length; i++) {
  for (var j = 0; j < resultado.vetor[i].length; j++) {
    if (j == 0) {
      resultado.vetor_string[i] = resultado.vetor[i][j] + ' > '
    }
    else if (j == resultado.vetor[i].length - 1) {
      resultado.vetor_string[i] += resultado.vetor[i][j]
    }
    else {
      resultado.vetor_string[i] += resultado.vetor[i][j] + ' > '
    }
  }
}

model.resultado = resultado.vetor_string
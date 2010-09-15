var caixasDeEntrada = search.luceneSearch(
	"workspace://SpacesStore", 'TYPE:"spu:CaixaEntrada"'
);

var caixaDeEntrada;
var processosCaixaEntrada;
var processos = new Array();
var path;
var j;
for (var i=0; i < caixasDeEntrada.length;i++) {
	caixaDeEntrada = caixasDeEntrada[i];
	path = caixaDeEntrada.getQnamePath();
	processosCaixaEntrada = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo"'
	);
	for (j = 0; j < processosCaixaEntrada.length; j++) {
		processos.push(processosCaixaEntrada[j]);
	}
}

model.processos = processos;

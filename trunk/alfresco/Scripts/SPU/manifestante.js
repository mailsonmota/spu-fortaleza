function getManifestantes(offset, pageSize, filter) {
	var searchQuery = 'TYPE:"spu:processo" AND PATH:"/app:company_home/cm:SPU/cm:PMF//*"';
	if (filter && filter != '') {
        searchQuery += ' AND (@spu\\:manifestante.Nome:"*' + filter + '*"';
        searchQuery += ' OR @spu\\:manifestante.Cpf:"' + filter + '*")';
    }
    
    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);
	
	var manifestantesPorCpf = new Array();
	var manifestante;
	var cpf;
	var created;
	var nome;
	var bairro;
	for (var i=0; i < processos.length; i++) {
		nome = processos[i].properties['spu:manifestante.Nome'];
		cpf = processos[i].properties['spu:manifestante.Cpf'];
		created = processos[i].properties.created;
		if (processos[i].properties['spu:manifestante.Bairro'] != null) {
			bairro = processos[i].properties['spu:manifestante.Bairro'];
		} else {
			bairro = '';
		}

		if (!manifestantesPorCpf[cpf] || (manifestantesPorCpf[cpf] && created > manifestantesPorCpf[cpf]['created'])) {
			manifestante = new Array();
			manifestante['created'] = created;
			manifestante['cpfCnpj'] = cpf;
			manifestante['nome'] = nome;
			manifestante['bairro'] = bairro;

			manifestantesPorCpf[cpf] = manifestante;
		}
	}

	var manifestantes = new Array();	
	for (var j in manifestantesPorCpf) {
		manifestantes.push(manifestantesPorCpf[j])
	}

	return manifestantes;
}

function mascararCpfCnpj(cpfCnpj) {
    cpfCnpjComMascara = cpfCnpj   
    if (cpfCnpj.length() == 11) {
        cpfCnpjComMascara = mascararCpf(cpfCnpj)
    } else if(cpfCnpj.length() == 14) {
        cpfCnpjComMascara = mascararCnpj(cpfCnpj)
    }
    return cpfCnpjComMascara
}

function mascararCpf(cpfCnpj) {
    cpfComMascara = cpfCnpj.substring(0, 3) + '.' 
                  + cpfCnpj.substring(3, 6) + '.' 
                  + cpfCnpj.substring(6, 9) + '-' 
                  + cpfCnpj.substring(9);

    return cpfComMascara
}

function getManifestante(cpfCnpj) {
    cpfCnpjMascarado = mascararCpfCnpj(cpfCnpj)
    var processos = search.luceneSearch('@spu\\:manifestante.Cpf:"' + cpfCnpjMascarado + '" OR ' +  
    									'@spu\\:manifestante.Cpf:"' + cpfCnpj + '"', 'created', false, 1);

    if (processos.length == 0) {
        throw 'Manifestante de CPF ou CNPJ ' + cpfCnpj + ' nao encontrado.'
    }

    return processos[0]
}

function getManifestantes() {
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:processo"');

	var manifestantesPorCpf = new Array();
	var manifestante;
	var cpf;
	var created;
	var nome;
	var bairro;
	for (var i=0; i < processos.length; i++) {
		nome = processos[i].properties['spu:processo.ManifestanteNome'];
		cpf = processos[i].properties['spu:processo.ManifestanteCpf'];
		created = processos[i].properties.created;
		if (processos[i].properties['spu:processo.ManifestanteBairro'] != null) {
			bairro = processos[i].properties['spu:processo.ManifestanteBairro'].name;
		} else {
			bairro = '';
		}

		if (!manifestantesPorCpf[cpf] || (manifestantesPorCpf[cpf] && created > manifestantesPorCpf[cpf]['created'])) {
			manifestante = new Array();
			manifestante['created'] = created;
			manifestante['cpf'] = cpf;
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

function getManifestante(cpf) {
	var processos = search.luceneSearch('@spu\\:processo.ManifestanteCpf:"' + cpf + '"');

	var manifestante;
	var cpf;
	var created;
	var nome;
	var bairro;
	for (var i=0; i < processos.length; i++) {
		nome = processos[i].properties['spu:processo.ManifestanteNome'];
		cpf = processos[i].properties['spu:processo.ManifestanteCpf'];
		created = processos[i].properties.created;
		if (processos[i].properties['spu:processo.ManifestanteBairro'] != null) {
			bairro = processos[i].properties['spu:processo.ManifestanteBairro'].name;
		} else {
			bairro = '';
		}

		if (!manifestante || (manifestante[cpf] && created > manifestante['created'])) {
			manifestante = new Array();
			manifestante['created'] = created;
			manifestante['cpf'] = cpf;
			manifestante['nome'] = nome;
			manifestante['bairro'] = bairro;
		}
	}

	return manifestante;
}

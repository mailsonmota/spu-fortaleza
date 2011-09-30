function getBairros() {
	var bairros = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"cm:generalclassifiable/cm:SPU/cm:Bairros/*"'
	);

	return bairros;
}

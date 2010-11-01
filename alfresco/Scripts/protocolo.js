var pastaRaizProtocolos = 'SPU';

function getProtocolos() {
	var protocolos = search.luceneSearch('+PATH:"/app:company_home/cm:PMF//*" +TYPE:"spu:Protocolo"');
	return protocolos;
}

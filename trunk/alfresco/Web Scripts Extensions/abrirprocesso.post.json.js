var props = new Array()

props["cm:name"] = json.get("numero")
//// props["spu:processo.Data"] = json.get("data")
props["spu:processo.Observacao"] = json.get("obs")
//props["spu:processo.Prioridade"] = json.get("prioridade")
props["spu:processo.NumeroOrigem"] = json.get("numeroorigem")
//// props["spu:processo.DataPrazo"] = json.get("data_prazo")
props["spu:processo.ManifestanteNome"] = json.get("manifestantenome")
props["spu:processo.ManifestanteCpf"] = json.get("manifestantecpfcnpj")
//props["spu:processo.ManifestanteTipo"] = json.get("manifestantetipo")
//// props["spu:processo.ManifestanteBairro"] = json.get("manifestantebairro")
//props["spu:processo.Assunto"] = json.get("assunto")

var node = userhome.createNode(null, "spu:processo", props)

model.variavel = node


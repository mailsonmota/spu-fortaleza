<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">
var processo = getNode('52973c3f-9740-4c57-86bb-4b61ba776192')
processoId = processo.properties['sys:node-uuid']
protocoloId = processo.properties['spu:processo.Destino']
prioridadeId = '37f3dc9b-f0f4-41bf-9829-1014766f315a'
prazo = '24/11/2010'
despacho = processo.properties['spu:processo.Despacho']
tramitar(processoId, protocoloId, prioridadeId, prazo, despacho)

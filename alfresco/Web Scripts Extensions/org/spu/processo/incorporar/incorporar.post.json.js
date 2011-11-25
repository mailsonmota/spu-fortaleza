<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var principalId = json.get("principal");
//var incorporadoId = json.get("incorporado");
var incorporados = json.get("incorporados");
incorporados = eval('(' + incorporados + ')');

var principalNode = getNode(principalId);
//var incorporadoNode = getNode(incorporadoId);

for (var i in incorporados) {
    var incorporadoNode = getNode(incorporados[i]);
    incorporadoNode.properties['spu:processo.Destino'] = principalId;
    incorporadoNode.save();

    var workflow = actions.create("start-workflow");
    workflow.parameters.workflowName = "jbpm$spu:incorporarProcesso"; 
    workflow.execute(incorporadoNode);
}

model.processo = principalNode;


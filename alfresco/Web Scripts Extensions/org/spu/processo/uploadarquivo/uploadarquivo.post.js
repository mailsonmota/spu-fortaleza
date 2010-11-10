<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">
var filename = null;
var content = null;
var node = null;
var nodeId = null;

for each (field in formdata.fields) {
    if (field.name == "file" && field.isFile) {
        filename = field.filename
        content = field.content
    } else if (field.name == "destNodeUuid") {
        nodeId = field.value
    }
}

if (filename == undefined || content == undefined) {
    status.code = 400
    status.message = "Arquivo do upload não pode ser encontrado"
    status.redirect = true
} else {
    node = getNode(nodeId)
    node = node.createFile(filename)
    node.properties.content.write(content)
    node.properties.encoding = "UTF-8"
    node.save()
    model.filename = filename
    model.content = content
    model.nodeId = nodeId
}


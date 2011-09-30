<import resource="/Company Home/Data Dictionary/Scripts/SPU/manifestante.js">

var params = url.extension.split('/')
var offset = params[0]
var pageSize = params[1]
var filter = args['s']

var manifestantes = getManifestantes(offset, pageSize, filter)
model.manifestantes = manifestantes

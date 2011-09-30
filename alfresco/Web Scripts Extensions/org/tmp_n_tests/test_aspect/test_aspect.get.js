<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processo = getNode('f6d2d872-cc96-4de7-bcbb-0105c679a398')
var varToEval = processo.properties['spu:folhas.Volumes'];
var arrayQwe = eval('(' + varToEval + ')');
throw arrayQwe[1]['nome']

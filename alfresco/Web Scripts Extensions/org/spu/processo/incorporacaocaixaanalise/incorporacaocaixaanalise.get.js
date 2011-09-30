<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var processoId = (url.templateArgs['processoId']) ? url.templateArgs['processoId'] : null;
var assuntoId = (url.templateArgs['assuntoId']) ? url.templateArgs['assuntoId'] : null;
var manifestanteCpf = (url.templateArgs['manifestanteCpf']) ? url.templateArgs['manifestanteCpf'] : null;
var offset = (url.templateArgs['offset']) ? url.templateArgs['offset'] : null;
var pageSize = (url.templateArgs['pageSize']) ? url.templateArgs['pageSize'] : null;
var filter = url.templateArgs['filter'];

model.processos = getCaixaAnaliseIncorporacao(processoId, assuntoId, manifestanteCpf, offset, pageSize, filter);

<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var params = url.extension.split('/')
var offset = params[0]
var pageSize = params[1]
var filter = params[2]

var filters = filter.split('\\+')
var searchQuery = '+PATH:"app:company_home/cm:SPU//*" AND (';

for (i = 0; i < filters.length; i++) {
    if (i > 0) {
        searchQuery += ' OR ';
    }
    searchQuery += 'TEXT:"*' + filters[i] + '*"'
}

searchQuery += ')';

model.anexos = search.luceneSearch("workspace://SpacesStore", searchQuery)

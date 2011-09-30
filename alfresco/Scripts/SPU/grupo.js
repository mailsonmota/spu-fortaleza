function getOrCreateGroup(parent, groupName) {
    var grupo = people.getGroup(groupName)
    if (!grupo) {
    	grupo = (parent) ? people.createGroup(parent, groupName) : people.createGroup(groupName)
        if (!grupo) {
        	throw groupName
        }
    }

    return grupo
}

function getRootGroup(shortName){
	var grupos = groups.getAllRootGroups()
	var grupo = null
	for(var i = 0; i < grupos.length; i++){
		if(grupos[i].getShortName().equals(shortName)){
			grupo = grupos[i];
			break;
		}
	}
	return grupo
}

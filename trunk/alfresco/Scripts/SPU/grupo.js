function getOrCreateGroup(parent, groupName) {
    var grupo = people.getGroup(groupName)
    if (!grupo) {
        grupo = (parent) ? people.createGroup(parent, groupName) : people.createGroup(groupName)
    }

    return grupo
}

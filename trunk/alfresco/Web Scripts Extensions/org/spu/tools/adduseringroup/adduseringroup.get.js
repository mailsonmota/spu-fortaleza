var group_name = args['group']
var user_name = args['user']

var group_node = people.getGroup(group_name)
var user_node = people.getPerson(user_name)

people.addAuthority(group_node, user_node)

model.resultado = 123


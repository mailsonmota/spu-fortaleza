model.fullname = person.properties.firstName;
if (person.properties.lastName != "")
{
model.fullname += ' ' + person.properties.lastName;
}
// locate folder by path
var folder = companyhome.childByNamePath("Data Dictionary/Tipos de Processo");
if (folder == undefined || !folder.isContainer)
{
	status.code = 404;
	status.message = "Folder " + url.extension + " not found.";
	status.redirect = true;
}
var tiposProcesso = folder.getChildren();
model.tiposProcesso = tiposProcesso;

function generateFormSpu(xsdFile,containerId) {
    try {

        //carrega o xml
        var xml = xmlLoader(xsdFile);
        var tagRaiz  = xml.getElementsByTagNameNS('http://www.w3.org/2001/XMLSchema','schema')[0];
        var elemRoot = getNodeByTagName(tagRaiz, 'xs:element'); // elemento raiz
        var elHtml = generateFormFromNode(tagRaiz, elemRoot, "xsdform___");
        getById(containerId).appendChild( elHtml );

    } catch (myError) {
        alert( myError.name + ': ' + myError.message + "\n" + myError);
    }
}
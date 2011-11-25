require 'json'
require 'curb'
require 'yaml'


def lerTiposDocumentos()
  rows = []
  file = File.open("/home/deivid/ged/tipoDocumentos.txt");
  estrutura = Array.new
  documentos = Array.new
  idxTemp = 1
  file.each_line do |line|
    idx = line.split(/\s+/)[0]
    #Divide cada nivel em um array para ser alocado dentro do subnivel correspondente
    line = line.gsub(/\.$/, "");
    if (idx != nil)
      if (idx.length() > 3)
        #nivel3.push(JSON 'indice'=>idx, 'nome'=>line.gsub(idx, ' ').strip())
        estrutura.push({'classificacao'=>idx[0..1], 'nivel'=> '3','nome'=>line})
      elsif (idx.length() == 3)
        #nivel2.push(JSON 'indice'=>idx, 'nome'=>line.gsub(idx, ' ').strip())
        estrutura.push({'classificacao'=>idx[0..1], 'nivel'=>'2', 'nome'=>line}) 
      elsif(idx.length() == 2)
        #nivel1.push(JSON 'indice'=>idx, 'nome'=>line.gsub(idx, ' ').strip())
        estrutura.push({'classificacao'=>idx[0..1],'nivel'=>'1', 'nome'=>line})
      end
    end
  end
  #estrutura.push(documentos)
  
  tiposDocumentos = JSON 'estrutura' => estrutura
  
  file.close
  file = criarArquivoJSON(tiposDocumentos)
  #criarTiposDocumentos(file);
  file.close 
end

def criarArquivoJSON(tiposDocumentos)
  file = File.new("tipoDocumento.json", 'w')
  file.write(tiposDocumentos) 
  return file
end

def criarTiposDocumentos(file)
  # post
  c = Curl::Easy.new("http://172.30.41.28:8080/alfresco/service/spu/gerar/tiposdocumentos")
  c.http_auth_types = :basic
  c.username = 'admin'
  c.password = 'admin'
  c.multipart_form_post = true
  c.http_post(Curl::PostField.file('file','tipoDocumento.json'))
  
  # print response
  y [c.response_code, c.body_str]
end


lerTiposDocumentos();

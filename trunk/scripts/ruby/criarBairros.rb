require 'json'

def lerBairros()
  rows = []
  file = File.open("BAIRROS.txt");
  estrutura = Array.new
  file.each_line do |line|
    idx = line.split(/\s+/)[0]
    #Divide cada nivel em um array para ser alocado dentro do subnivel correspondente
    line = line.gsub(/\.$/, "");
    if (idx != nil)
      estrutura.push({:nome=>line})
    end
  end
  
  bairros = JSON 'bairros' => estrutura
  
  print bairros
  
  file = criarArquivoJSON(bairros)
  file.close 
end  

def criarArquivoJSON(bairros)
  file = File.new("bairros.json", 'w')
  file.write(bairros) 
  return file
end

lerBairros()
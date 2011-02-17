#!/bin/sh

clear
echo "Script de backup do Data Dictionary do Alfresco\n"

ALFRESCO_WEBDAV_ADDRESS="http://172.30.116.21:8080/alfresco/webdav"
ALFRESCO_WEBDAV_MOUNT_POINT="/mnt/alfrescowebdav"

date_now=`date +%Y-%m-%d_%H-%M-%S`
filename="/tmp/alfresco_data_dictionary_backup_${date_now}.tar.bz2"

# Caso o diretório informado como ponto de montagem para o WebDAV não seja válido
if [ ! -d $ALFRESCO_WEBDAV_MOUNT_POINT ]
then
    echo "ERRO >>> O seguinte diretório informado como ponto de montagem\npara o WebDAV não é válido:\n${ALFRESCO_WEBDAV_MOUNT_POINT}"
    return 1
fi

# Caso o diretório informado como destino do arquivo de backup não seja válido
if [ -n $1 ] && [ ! -d $1 ]
then
    echo "ERRO >>> O seguinte endereço de destino para onde seria movido o\narquivo de backup não é válido: ${1}"
    return 1
fi

# Informações ao usuário
if [ -z $1 ]
then
    echo "INFO >>> Nenhum diretório foi escolhido para onde mover o arquivo de backup.\nApós o processamento do script, ele estará então em seu local default:\n${filename}\n"
else
    echo "INFO >>> O seguinte diretório foi escolhido como destino para onde copiar o\narquivo de backup:\n${1}\n"
fi

echo "INFO >>> Montando o WebDAV em /mnt/alfrescowebdav\n"
sudo mount.davfs $ALFRESCO_WEBDAV_ADDRESS $ALFRESCO_WEBDAV_MOUNT_POINT

echo "\nINFO >>> Copiando o Data Dictionary para /tmp\n"
sudo cp -rv ${ALFRESCO_WEBDAV_MOUNT_POINT}/Data\ Dictionary /tmp

echo "\nINFO >>> Compactando o Data Dictionary (para um arquivo tar.bz2)\n"
sudo tar -cjf $filename -C /tmp/ Data\ Dictionary

echo "INFO >>> Apagando os arquivos temporários\n"
sudo rm -rf /tmp/Data\ Dictionary

echo "INFO >>> Dando permissão sobre o arquivo de backup ao usuário $USER\n"
sudo chown $USER:$USER $filename

if [ -z $1 ]
then
    echo "INFO >>> Avisando novamente: nenhum diretório foi escolhido para onde mover o\narquivo de backup. Ele se encontra então em seu local default:\n${filename}\n"
else
    echo "INFO >>> Movendo o arquivo de backup para ${1}\n"
    mv $filename $1
fi

echo "INFO >>> Desmontando o diretório do Alfresco"
sudo umount $ALFRESCO_WEBDAV_MOUNT_POINT


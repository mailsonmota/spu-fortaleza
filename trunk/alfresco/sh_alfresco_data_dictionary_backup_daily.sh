#!/bin/sh
today=`date +%Y-%m-%d`
found=0
for i in alfresco_data_dictionary_backup_${today}*; do
	if [ $i != "alfresco_data_dictionary_backup_${today}*" ] ; then
		found=1
	fi
done
if [ $found -eq 1 ] ; then #se ja existe um backup desse dia
	exit
fi
./sh_alfresco_data_dictionary_backup_auto.sh

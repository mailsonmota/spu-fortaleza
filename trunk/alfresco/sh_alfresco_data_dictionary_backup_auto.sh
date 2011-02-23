#!/usr/bin/expect
set timeout 10000
spawn ./sh_alfresco_data_dictionary_backup.sh .
expect sername:
send alfresco\r
expect assword:
send alfresco\r
expect closed
exit 0

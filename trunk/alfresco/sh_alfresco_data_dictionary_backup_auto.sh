#!/usr/bin/expect
set timeout 10000
spawn ./sh_alfresco_data_dictionary_backup.sh .
expect sername:
send admin\r
expect assword:
send admin\r
expect closed
exit 0

<?php
$ds=ldap_connect("dodaydream.local");
ldap_set_option ($ds, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION, 3);
$dsbind=ldap_bind($ds,"CN=Administrator,CN=Users,DC=dodaydream,DC=local", "D0d@ydream#MIS2017");

<tr class="ui-state-disabled"><td><b>Drag User</b></td></tr>
<?php
require_once "../../Tool/conldap.php";
$dept=filter_input(INPUT_GET,"dep",FILTER_SANITIZE_STRING);
$query=ldap_search($ds,"OU=".$dept.",OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(userAccountControl=512))",["displayName","sAMAccountName"]);
$dataen=ldap_get_entries($ds, $query);
for ($i=0; $i <$dataen["count"] ; $i++) { ?>
<tr class="cur userdata" data-idu="<?= $dataen[$i]["samaccountname"][0] ?>"><td><?= $dataen[$i]["displayname"][0] ?></td></tr>
<?php } ?>

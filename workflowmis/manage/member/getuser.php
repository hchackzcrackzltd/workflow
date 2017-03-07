<tr class="ui-state-disabled"><td><b>Drag User</b></td></tr>
<?php
require_once "../../Tool/conldap.php";
require_once "../../Tool/condb.php";
$dept=filter_input(INPUT_GET,"dep",FILTER_SANITIZE_STRING);
$sqluser=$db->prepare("SELECT * FROM user_logon WHERE id_dept=? AND type!=?");
$sqluser->execute([$dept,"ad"]);
$datauser=$sqluser->fetchAll(PDO::FETCH_OBJ);
foreach ($datauser as $value) {
$query=ldap_search($ds,"OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(userAccountControl=512)(sAMAccountName=".$value->id_user."))",["displayName","sAMAccountName"]);
$dataen=ldap_get_entries($ds, $query);
 ?>
<tr class="cur userdatam" data-idu="<?= (isset($dataen[0]["samaccountname"][0]))?$dataen[0]["samaccountname"][0]:$value->id_user ?>"><td><?= (isset($dataen[0]["displayname"][0]))?$dataen[0]["displayname"][0]:$value->id_user ?></td></tr>
<?php } ?>

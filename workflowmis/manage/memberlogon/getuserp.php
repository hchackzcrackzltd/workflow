<tr class="ui-state-disabled"><td><b>Drop User Here</b></td></tr>
<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
$dept=filter_input(INPUT_GET, "dept",FILTER_SANITIZE_STRING);
$type=filter_input(INPUT_GET, "type",FILTER_SANITIZE_STRING);
$sqlus=$db->prepare("SELECT * FROM user_logon WHERE id_dept=? AND type=?");
$sqlus->execute([$dept,$type]);
$dataus=$sqlus->fetchAll(PDO::FETCH_OBJ);
foreach ($dataus as $value) {
  $querylp=ldap_search($ds,"OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$value->id_user."))",["displayName","sAMAccountName"]);
  $datalp=ldap_get_entries($ds, $querylp);
 ?>
 <tr class="cur userdata" data-idu="<?= $datalp[0]["samaccountname"][0] ?>">
   <td><?= (isset($datalp[0]["displayname"][0]))?$datalp[0]["displayname"][0]:$value->id_user ?></td>
 </tr>
 <?php }  ?>

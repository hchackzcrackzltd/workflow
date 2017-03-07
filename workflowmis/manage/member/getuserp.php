<tr class="ui-state-disabled"><td><b>Drop User Here</b></td></tr>
<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
$idj=filter_input(INPUT_GET, "idj",FILTER_SANITIZE_STRING);
$dept=filter_input(INPUT_GET, "dept",FILTER_SANITIZE_STRING);
$typ=filter_input(INPUT_GET, "typ",FILTER_SANITIZE_STRING);
$dept=isset($dept)?$dept:"101";
$sqlus=$db->prepare("SELECT * FROM project_member WHERE id_proj=? AND id_dept=? AND type=?");
$sqlus->execute([$idj,$dept,$typ]);
$dataus=$sqlus->fetchAll(PDO::FETCH_OBJ);
foreach ($dataus as $value) {
  $querylp=ldap_search($ds,"OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$value->id_user."))",["displayName","sAMAccountName"]);
  $datalp=ldap_get_entries($ds, $querylp);
 ?>
 <tr class="cur userdatam" data-idu="<?= (isset($datalp[0]["samaccountname"][0]))?$datalp[0]["samaccountname"][0]:$value->id_user ?>">
   <td><?= (isset($datalp[0]["displayname"][0]))?$datalp[0]["displayname"][0]:$value->id_user ?></td>
 </tr>
 <?php }  ?>

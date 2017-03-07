<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
require_once "../../logon/checkauth.php";
$data=[];
  foreach ($db->query("SELECT * FROM user_logon") as $value) {
    $dq=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local","(&(objectClass=person)(sAMAccountName~=".$value["id_user"]."))", ["displayName","sAMAccountName"]);
    $datalp=ldap_get_entries($ds, $dq);
    $data[isset($datalp[0]["displayname"][0])?$datalp[0]["displayname"][0]:$value["id_user"]]=NULL;
  }
echo json_encode($data);
 ?>

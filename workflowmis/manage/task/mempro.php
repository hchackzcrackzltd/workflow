<?php
 require_once "../../Tool/condb.php";
 require_once "../../Tool/conldap.php";
 $idj=filter_input(INPUT_GET, "idj" ,FILTER_SANITIZE_STRING);
 $data=[];
 foreach ($db->query("SELECT * FROM project_member WHERE id_proj='$idj'") as $value) {
   $dq=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local","(&(objectClass=person)(sAMAccountName~=".$value["id_user"]."))", ["displayName","sAMAccountName"]);
   $datalp=ldap_get_entries($ds, $dq);
   $data[isset($datalp[0]["displayname"][0])?$datalp[0]["displayname"][0]:$value["id_user"]]=NULL;
 }
 echo json_encode($data);
 ?>

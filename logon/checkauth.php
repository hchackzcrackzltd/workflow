<?php
session_start();
error_reporting(0);
$iduser = filter_var($_SESSION['iduser'], FILTER_SANITIZE_STRING);
if ($iduser!=NULL) {
    $sr=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=user)(objectCategory=person)(samaccountname=$iduser))",["cn","description","mail", "telephonenumber", "department", "title","displayName"]);
    $info = ldap_get_entries($ds, $sr);
    $userlgo=$db->prepare("SELECT * FROM user_logon WHERE id_user=?");
    $userlgo->execute([$iduser]);
    $userlgod=$userlgo->fetch(PDO::FETCH_ASSOC);
    $typeuser=$userlgod["type"];
    $deptuser=$userlgod["id_dept"];
    $texttype=($typeuser=="ad")?"Administrator":"User";
} else {
    header('Location: index.php');
    exit();
}
?>

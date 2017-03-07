<?php
require_once "../../Tool/condb.php";
require_once "../../logon/checkauth.php";
$id=filter_input(INPUT_GET,"id",FILTER_SANITIZE_STRING);
echo $id;
try {
  if($iduser!=$id){
  $sqldeluser=$db->prepare("DELETE FROM user_logon WHERE id_user=?");
  $sqldeluser->execute([$id]);
  $sql=$db->prepare("DELETE FROM project_member WHERE id_user=?");
  $sql->execute([$id]);
}
} catch (PDOException $e) {
  $db->rollBack();
}
 ?>

<?php
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
try {
  $sql=$db->prepare("DELETE FROM department WHERE id=?");
  $sql->execute([$id]);
  $sql2=$db->prepare("DELETE FROM project_member WHERE id_dept=?");
  $sql2->execute([$id]);
  $sql3=$db->prepare("UPDATE user_logon SET id_dept=? WHERE id_dept=?");
  $sql3->execute(["1000",$id]);
} catch (PDOException $e) {
  $db->rollBack();
}
 ?>
user_logon

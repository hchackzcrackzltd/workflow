<?php
require_once "../../Tool/condb.php";
$idp=filter_input(INPUT_POST, "idp",FILTER_SANITIZE_STRING);
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
try {
  $sql=$db->prepare("DELETE FROM task WHERE id=? AND idproc=?");
  $sql->execute([$id,$idp]);
  $sql1=$db->prepare("DELETE FROM assign_task WHERE idtask=? AND idproc=?");
  $sql1->execute([$id,$idp]);
  echo "Task as been deleted";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

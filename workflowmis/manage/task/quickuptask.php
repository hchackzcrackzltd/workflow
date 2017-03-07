<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
$psdp=filter_input(INPUT_POST, "psid",FILTER_SANITIZE_STRING);
$per=filter_input(INPUT_POST, "per",FILTER_SANITIZE_NUMBER_INT);
try {
  $sql=$db->prepare("UPDATE task SET complete=? WHERE id=? AND idproc=?");
  $sql->execute([$per,$id,$psdp]);
  echo "Success";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

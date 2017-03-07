<?php
include_once "../../Tool/condb.php";
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
try {
  $sqldel=$db->prepare("DELETE FROM flow_line WHERE id=?");
  $sqldel->execute([$id]);
  echo "SUC";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage()."\n";
}
 ?>

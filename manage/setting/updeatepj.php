<?php
require_once "../../Tool/condb.php";
$idj=filter_input(INPUT_POST,"idj",FILTER_SANITIZE_STRING);
$project_name=filter_input(INPUT_POST,"project_name",FILTER_SANITIZE_STRING);
$owner=filter_input(INPUT_POST,"owner",FILTER_SANITIZE_STRING);
$Description=filter_input(INPUT_POST,"Description",FILTER_SANITIZE_STRING);
$due_date=filter_input(INPUT_POST,"due_date",FILTER_SANITIZE_STRING);
$due_date.=" 23:59:00";
try {
  $sql=$db->prepare("UPDATE project_info SET name=?,description=?,owner=?,end_date=? WHERE id=?");
  $sql->execute([$project_name,$Description,$owner,$due_date,$idj]);
  echo "Project Updated";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

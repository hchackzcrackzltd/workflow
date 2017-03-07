<?php
require_once "../../Tool/condb.php";
$idj=filter_input(INPUT_POST,"idj",FILTER_SANITIZE_STRING);
$dep=filter_input(INPUT_POST,"dep",FILTER_SANITIZE_STRING);
$status=null;
try {
  $sql=$db->prepare("SELECT * FROM project_department WHERE id_project=? AND	id_department=?");
  $sql->execute([$idj,$dep]);
  $sqldata=$sql->fetch(PDO::FETCH_COLUMN);
if(!$sqldata){
  $sql1=$db->prepare("INSERT INTO project_department(id_project,id_department) VALUES (?,?)");
  $sql1->execute([$idj,$dep]);
echo "Success";
}else {
echo "This department was have already";
}
} catch (PDOException $e) {
  $db->rollBack();
echo $e->getMessage();
}
 ?>

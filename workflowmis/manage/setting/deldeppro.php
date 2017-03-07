<?php
require_once "../../Tool/condb.php";
$idj=filter_input(INPUT_POST,"idj",FILTER_SANITIZE_STRING);
$idd=filter_input(INPUT_POST,"idd",FILTER_SANITIZE_STRING);
try {
  $sql1=$db->prepare("SELECT * FROM project_process WHERE id_project=? AND id_department=?");
  $sql1->execute([$idj,$idd]);
  $sql2=$db->prepare("SELECT * FROM project_member WHERE id_proj=? AND id_dept=?");
  $sql2->execute([$idj,$idd]);
  $data1=$sql1->fetchAll(PDO::FETCH_OBJ);
  $data2=$sql2->fetchAll(PDO::FETCH_OBJ);
  if(count($data1)<1&&count($data2)<1){
    $sql3=$db->prepare("DELETE FROM project_department WHERE id_project=? AND id_department=?");
    $sql3->execute([$idj,$idd]);
    echo "Department has been deleted";
  }else{
    echo "Have user or process this department, Please Check it";
  }
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}
 ?>

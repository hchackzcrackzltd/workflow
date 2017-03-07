<?php
require_once "../../Tool/condb.php";
$idp=filter_input(INPUT_POST, "idp",FILTER_SANITIZE_STRING);
$idj=filter_input(INPUT_POST, "idj",FILTER_SANITIZE_STRING);
$name=filter_input(INPUT_POST, "name",FILTER_SANITIZE_STRING);
$str_date=filter_input(INPUT_POST, "str_date",FILTER_SANITIZE_STRING);
$end_date=filter_input(INPUT_POST, "end_date",FILTER_SANITIZE_STRING);
$reminder=filter_input(INPUT_POST, "reminder",FILTER_SANITIZE_STRING);
$owner=filter_input(INPUT_POST, "owner",FILTER_SANITIZE_STRING);
$description=filter_input(INPUT_POST, "description",FILTER_SANITIZE_STRING);
$assign=filter_var_array($_REQUEST["assgn"],FILTER_SANITIZE_STRING);
try {
  $sqlid=$db->prepare("SELECT MAX(id) AS ID FROM task WHERE idproj=? AND idproc=?");
  $sqlid->execute([$idj,$idp]);
  $sqliddata=$sqlid->fetchColumn();
  if(isset($sqliddata)){
      $numberformat=($sqliddata)+1;
  }else{
      $numberformat='1001';
  }
  $sql=$db->prepare("INSERT INTO task(id,idproj,idproc,priority,name,strdate,enddate,status,complete,ownner,reminder,detail) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
  $sql->execute([$numberformat,$idj,$idp,0,$name,$str_date,$end_date,"pd",0,$owner,$reminder,$description]);
  foreach ($assign as $value) {
    //if($value<>$owner){
    $sqlass=$db->prepare("INSERT INTO assign_task(idtask,idproc,user) VALUES (?,?,?)");
    $sqlass->execute([$numberformat,$idp,$value]);
  //}
  }
  echo "Success";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

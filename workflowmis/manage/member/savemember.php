<?php
require_once "../../Tool/condb.php";
require_once "../../logon/checkauth.php";
$idj=filter_input(INPUT_POST, "idj",FILTER_SANITIZE_STRING);
$iddept=filter_input(INPUT_POST, "iddept",FILTER_SANITIZE_STRING);
$iduser=array_unique(json_decode(filter_var($_REQUEST["iduser"])));
$typ=filter_input(INPUT_POST, "typ",FILTER_SANITIZE_STRING);
try {
  if(count($iduser)>0){
    $sqldeluser=$db->prepare("DELETE FROM project_member WHERE id_proj=? AND id_dept=? AND type=?");
    $sqldeluser->execute([$idj,$iddept,$typ]);
  foreach ($iduser as $value) {
    if($typ=="ad"){
      $sqldeluser=$db->prepare("DELETE FROM project_member WHERE id_proj=? AND id_user=?");
      $sqldeluser->execute([$idj,$value]);
      $iddept="101";
    }else{
      $sqldeluser=$db->prepare("DELETE FROM project_member WHERE id_proj=? AND id_user=? AND type=?");
      $sqldeluser->execute([$idj,$value,"ad"]);
    }
    $sqlmem=$db->prepare("INSERT INTO project_member(id_proj,id_dept,id_user,type) VALUES (?,?,?,?)");
    $sqlmem->execute([$idj,$iddept,$value,$typ]);
  }
}else{
  $sqldeluser=$db->prepare("DELETE FROM project_member WHERE id_proj=? AND id_dept=? AND type=?");
  $sqldeluser->execute([$idj,$iddept,$typ]);
}
echo "Success";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

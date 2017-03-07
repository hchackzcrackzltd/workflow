<?php
require_once "../../Tool/condb.php";
require_once "../../logon/checkauth.php";
$iddept=filter_input(INPUT_POST, "iddept",FILTER_SANITIZE_STRING);
$type=filter_input(INPUT_POST, "type",FILTER_SANITIZE_STRING);
$iduserin=array_unique(json_decode(filter_var($_REQUEST["iduser"])));
try {
if(count($iduserin)>0){
  foreach ($iduserin as $value) {
    $sqldeluser=$db->prepare("DELETE FROM user_logon WHERE id_user=?");
    $sqldeluser->execute([$value]);
    $sqlmem=$db->prepare("INSERT INTO user_logon(id_dept,id_user,type) VALUES (?,?,?)");
    $sqlmem->execute([$iddept,$value,$type]);
  }
}
echo "Success";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

<?php
require_once "../../Tool/condb.php";
$idp=filter_input(INPUT_POST, "idp",FILTER_SANITIZE_STRING);
$idt=filter_input(INPUT_POST, "idt",FILTER_SANITIZE_STRING);
$name=filter_input(INPUT_POST, "name",FILTER_SANITIZE_STRING);
$str_date=filter_input(INPUT_POST, "str_date",FILTER_SANITIZE_STRING);
$end_date=filter_input(INPUT_POST, "end_date",FILTER_SANITIZE_STRING);
$reminder=filter_input(INPUT_POST, "reminder",FILTER_SANITIZE_STRING);
$owner=filter_input(INPUT_POST, "owner",FILTER_SANITIZE_STRING);
$description=filter_input(INPUT_POST, "description",FILTER_SANITIZE_STRING);
$complete=filter_input(INPUT_POST, "complete",FILTER_SANITIZE_NUMBER_INT);
$priority=filter_input(INPUT_POST, "priority",FILTER_SANITIZE_NUMBER_INT);
$file=filter_var_array($_FILES["fileat"]);
$assgn=filter_var_array($_REQUEST["assgn"],FILTER_SANITIZE_STRING);
$statusf=true;$ferr=null;
if(strlen($file["name"][0])>0){
foreach ($file["type"] as $int=>$tmp) {
  if($tmp<>"application/pdf"&&$tmp<>"image/jpeg"){
    $statusf=false;
    $ferr=$file["name"][$int]." Not Support File This Type";
  }
}
foreach ($file["size"] as $int1=>$tmp1) {
  if($tmp>5347737){
    $statusf=false;
    $ferr=$file["name"][$int1]." This File Size More Then 5 MB";
  }
}
if($statusf){
foreach ($file["tmp_name"] as $int2=>$tmp2) {
  if(is_uploaded_file($tmp2)){
    try {
      $namecp=null;$statuslop=true;
      $nameran=array_merge(range('A','Z'),range('0','9'),range('a','z'));
      while ($statuslop) {
        foreach (range(1,8) as $lop) {$namecp.=$nameran[array_rand($nameran)];}
        $sqlfck=$db->prepare("SELECT COUNT(*) AS ct FROM task_at WHERE filer=?");
        $sqlfck->execute([$namecp]);
        $datafck=$sqlfck->fetch(PDO::FETCH_COLUMN);
        if($datafck<>0){$namecp=null;}else{$statuslop=false;}
      }
      $namecomb=$namecp.strstr($file["name"][$int2],'.');
      $sqlf=$db->prepare("INSERT INTO task_at(id_proc,id_task,filen,filer) VALUES (?,?,?,?)");
      $sqlf->execute([$idp,$idt,$file["name"][$int2],$namecomb]);
      move_uploaded_file($tmp2,"../../attatchment/".$namecomb);
    } catch (PDOException $e) {
      $db->rollBack();
      echo $e->getMessage();
    }
  }else{
    $ferr="Error Upload File ".basename($file["name"][$int2]);
  }
}
}
}
try {
  $status=($complete==100)?"cp":"pd";
  $sql=$db->prepare("UPDATE task SET priority=?,name=?,strdate=?,enddate=?,status=?,complete=?,ownner=?,reminder=?,detail=? WHERE idproc=? AND id=?");
  $sql->execute([$priority,$name,$str_date,$end_date,$status,$complete,$owner,$reminder,$description,$idp,$idt]);
  $sqlassdel=$db->prepare("DELETE FROM assign_task WHERE idtask=? AND idproc=?");
  $sqlassdel->execute([$idt,$idp]);
  foreach ($assgn as $valueass) {
    //if($valueass<>$owner){
    $sqlass=$db->prepare("INSERT INTO assign_task(idtask,idproc,user) VALUES (?,?,?)");
    $sqlass->execute([$idt,$idp,$valueass]);
  //}
  }
  echo (isset($ferr))?$ferr:"Success";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}

 ?>

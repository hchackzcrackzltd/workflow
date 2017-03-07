<?php
require_once "../../Tool/condb.php";
$idp=filter_input(INPUT_POST,"idp",FILTER_SANITIZE_STRING);
$pointto=filter_input(INPUT_POST,"pointto",FILTER_SANITIZE_STRING);
$pointfrom=filter_input(INPUT_POST,"pointform",FILTER_SANITIZE_STRING);
$invert=filter_input(INPUT_POST,"invert",FILTER_SANITIZE_STRING);
$offset=filter_input(INPUT_POST,"offset",FILTER_SANITIZE_STRING);
$linetype=filter_input(INPUT_POST,"linetype",FILTER_SANITIZE_STRING);
try {
  if(isset($offset)){
  $sqlckof=$db->prepare("SELECT * FROM offset_pro WHERE id_project=? AND id_process=?");
  $sqlckof->bindValue(1,$idp,FILTER_SANITIZE_STRING);
  $sqlckof->bindValue(2,$pointfrom,FILTER_SANITIZE_STRING);
  $sqlckof->execute();
  $datackof=$sqlckof->fetch(PDO::FETCH_OBJ);
  if(isset($datackof->id_project)){
    $sqldelof=$db->prepare("DELETE FROM offset_pro WHERE id_project=? AND id_process=?");
    $sqldelof->bindValue(1,$idp,PDO::PARAM_STR);
    $sqldelof->bindValue(2,$pointfrom,PDO::PARAM_STR);
    $sqldelof->execute();
  }
  $sqlintof=$db->prepare("INSERT INTO offset_pro(id_project,id_process,offset) VALUES (?,?,?)");
  $sqlintof->bindValue(1,$idp,PDO::PARAM_STR);
  $sqlintof->bindValue(2,$pointfrom,PDO::PARAM_STR);
  $sqlintof->bindValue(3,$offset,PDO::PARAM_STR);
  $sqlintof->execute();
}
if(isset($pointto)){
  $datasql1=$db->prepare("SELECT * FROM flow_line WHERE point_to=? AND point_from=? AND id_project=?");
  $datasql1->bindValue(1,$pointto,PDO::PARAM_STR);
  $datasql1->bindValue(2,$pointfrom,PDO::PARAM_STR);
  $datasql1->bindValue(3,$idp,PDO::PARAM_STR);
  $datasql1->execute();
  $shdata=$datasql1->fetch(PDO::FETCH_OBJ);
  if(isset($shdata->id_project)){
    $datasql=$db->prepare("DELETE FROM flow_line WHERE point_to=? AND point_from=? AND id_project=?");
    $datasql->bindValue(1,$pointto,PDO::PARAM_STR);
    $datasql->bindValue(2,$pointfrom,PDO::PARAM_STR);
    $datasql->bindValue(3,$idp,PDO::PARAM_STR);
    $datasql->execute();
  }
  $date = new DateTime();
  $numberformat = $date->format("ymd");
  $sqlrow=$db->prepare("SELECT MAX(id) AS ID FROM flow_line WHERE id LIKE '$numberformat" . "%'");
  $sqlrow->execute();
  $datacol = $sqlrow->fetchColumn();
  if (isset($datacol)) {
      $numberformat = ($datacol) + 1;
  } else {
      $numberformat.='001';
  }
  $datasql=$db->prepare("INSERT INTO flow_line(id,id_project,point_from,point_to,invert_point,line_type) VALUES (?,?,?,?,?,?)");
  $datasql->bindValue(1,$numberformat,PDO::PARAM_STR);
  $datasql->bindValue(2,$idp,PDO::PARAM_STR);
  $datasql->bindValue(3,$pointfrom,PDO::PARAM_STR);
  $datasql->bindValue(4,$pointto,PDO::PARAM_STR);
  $datasql->bindValue(5,$invert,PDO::PARAM_STR);
  $datasql->bindValue(6,$linetype,PDO::PARAM_STR);
  $datasql->execute();
}
  echo "Success";
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage()."\n";
}

 ?>

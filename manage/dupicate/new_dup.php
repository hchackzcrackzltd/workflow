<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
require_once "../../logon/checkauth.php";
$pro_name=filter_input(INPUT_POST, "project_named",FILTER_SANITIZE_STRING);
$description=filter_input(INPUT_POST, "Descriptiond",FILTER_SANITIZE_STRING);
$str_time=filter_input(INPUT_POST, "str_timed",FILTER_SANITIZE_STRING);
$end_time=filter_input(INPUT_POST, "str_endd",FILTER_SANITIZE_STRING);
$owner=filter_input(INPUT_POST, "ownerd",FILTER_SANITIZE_STRING);
$idj=filter_input(INPUT_POST, "idproject",FILTER_SANITIZE_STRING);
$date=new DateTime();
$numberformat=$date->format("ymd");
try {
  $sqlid=$db->prepare("SELECT MAX(id) AS ID FROM project_info WHERE id LIKE '$numberformat"."%'");
  $sqlid->execute();
  $sqliddata=$sqlid->fetchColumn();
  if(isset($sqliddata)){
      $numberformat=($sqliddata)+1;
  }else{
      $numberformat.='001';
  }
  $strf=new DateTime($str_time);
  $str_time=$strf->format("Y-m-d");
  $endf=new DateTime($end_time);
  $end_time=$endf->format("Y-m-d");
  $str_time.=" 00:00:00";
  $end_time.=" 23:59:00";
  $sqlckp=$db->prepare("SELECT COUNT(*) AS A FROM project_info WHERE id=?");
  $sqlckp->execute([$idj]);
  $sqlckpdata=$sqlckp->fetch(PDO::FETCH_ASSOC);
  if($sqlckpdata["A"]<>0){
    $sql=$db->prepare("INSERT INTO project_info(id,name,str_date,end_date,description,owner) VALUES (?,?,?,?,?,?)");
    $sql->execute([$numberformat,$pro_name,$str_time,$end_time,$description,$owner]);
    foreach ($db->query("SELECT * FROM project_process WHERE id_project='$idj'") as $value) {
      $sql1=$db->prepare("INSERT INTO project_process(id,id_project,name,id_department) VALUES (?,?,?,?)");
      $sql1->execute([$numberformat.substr($value["id"],-3),$numberformat,$value["name"],$value["id_department"]]);
    }
    foreach ($db->query("SELECT * FROM project_department WHERE id_project='$idj'") as $value1) {
      $sql2=$db->prepare("INSERT INTO project_department(id_project,id_department) VALUES (?,?)");
      $sql2->execute([$numberformat,$value1["id_department"]]);
    }
    foreach ($db->query("SELECT * FROM offset_pro WHERE id_project='$idj'") as $value2) {
      $sql3=$db->prepare("INSERT INTO offset_pro(id_project,id_process,offset) VALUES (?,?,?)");
      $sql3->execute([$numberformat,$numberformat.substr($value2["id_process"],-3),$value2["offset"]]);
    }
    foreach ($db->query("SELECT * FROM pre_process WHERE id_proc LIKE '".$idj."%'") as $value3) {
      $sql4=$db->prepare("INSERT INTO pre_process(id_proc,id_pre) VALUES (?,?)");
      $sql4->execute([$numberformat.substr($value3["id_proc"],-3),$numberformat.substr($value3["id_pre"],-3)]);
    }
    foreach ($db->query("SELECT * FROM flow_line WHERE id_project='$idj'") as $value3) {
      $numberformat1 = $date->format("ymd");
      $sqlrow=$db->prepare("SELECT MAX(id) AS ID FROM flow_line WHERE id LIKE '$numberformat1" . "%'");
      $sqlrow->execute();
      $datacol = $sqlrow->fetchColumn();
      if (isset($datacol)) {
          $numberformat1 = ($datacol) + 1;
      } else {
          $numberformat1.='001';
      }
      $sql4=$db->prepare("INSERT INTO flow_line(id,id_project,point_from,point_to,invert_point,line_type) VALUES (?,?,?,?,?,?)");
      $sql4->execute([$numberformat1,$numberformat,$numberformat.substr($value3["point_from"],-3),$numberformat.substr($value3["point_to"],-3),$value3["invert_point"],$value3["line_type"]]);
    }
    echo "Saved";
  }
} catch (PDOException $e) {
  $db->rollBack();
  echo $e->getMessage();
}
 ?>

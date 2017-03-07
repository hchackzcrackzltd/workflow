<?php

error_reporting(0);
require_once '../../Tool/condb.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$idproc = filter_input(INPUT_POST, "idprocess", FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, "process_name", FILTER_SANITIZE_STRING);
$str_date = filter_input(INPUT_POST, "str_proc", FILTER_SANITIZE_STRING);
$end_date = filter_input(INPUT_POST, "end_proc", FILTER_SANITIZE_STRING);
$department = filter_input(INPUT_POST, "department", FILTER_SANITIZE_STRING);
$pre_proc = filter_var_array($_REQUEST['pre_proc']);
$err = (!isset($department)) ? "Please Specify Department\n" : NULL;
$err .= (count($pre_proc) < 1) ? "Please Specify Process" : NULL;
if ($err != NULL) {
    echo $err;
    exit();
}
$str_date.=" 00:00:00";
$end_date.=" 23:59:00";
$statusdate=true;
$d11=new DateTime($str_date);
$d22=new DateTime($end_date);
try {
    $sqlcktk=$db->prepare("SELECT * FROM task WHERE idproc=?");
    $sqlcktk->execute([$idproc]);
    $datacktk=$sqlcktk->fetchAll(PDO::FETCH_ASSOC);
    foreach ($datacktk as $datatk) {
      $d1=new DateTime($datatk->strdate." 00:00:00");
      $d2=new DateTime($datatk->enddate." 23:59:00");
      if($d1<>$d11){
        $statusdate=false;
      }if($d2>$d22){
        $statusdate=false;
      }
    }
    if($statusdate){
    $datasql = $db->prepare("UPDATE project_process SET id_department='$department',name='$name',str_date='$str_date',end_date='$end_date' WHERE id='$idproc'");
    $datasql->execute();
    $datasqldel = $db->prepare("DELETE FROM pre_process WHERE id_proc='$idproc'");
    $datasqldel->execute();
    if(in_array("F",$pre_proc)){
      $datasqlnp = $db->prepare("INSERT INTO pre_process(id_proc,id_pre) VALUES (?,?)");
      $datasqlnp->execute([$idproc,"F"]);
    }else {
      foreach ($pre_proc as $data) {

          $datasqlnp = $db->prepare("INSERT INTO pre_process(id_proc,id_pre) VALUES ('$idproc','$data')");
          $datasqlnp->execute();
      }
    }
    echo "Saved";
  }else{
    echo "Please Check Date Start Or Due Date In Task";
  }
} catch (PDOException $ex) {
    echo $ex->getLine() . " " . $ex->getMessage();
    $db->rollBack();
}

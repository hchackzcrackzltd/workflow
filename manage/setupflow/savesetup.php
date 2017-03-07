<?php
require_once '../../Tool/condb.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$pro_name=filter_input(INPUT_POST, "project_name",FILTER_SANITIZE_STRING);
$description=filter_input(INPUT_POST, "Description",FILTER_SANITIZE_STRING);
$str_time=filter_input(INPUT_POST, "str_time",FILTER_SANITIZE_STRING);
$end_time=filter_input(INPUT_POST, "str_end",FILTER_SANITIZE_STRING);
$owner=filter_input(INPUT_POST, "owner",FILTER_SANITIZE_STRING);
$department= explode(",", filter_var($_REQUEST["departvalue"]));
$process= json_decode(filter_var($_REQUEST["datachip"]));
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
    $str_time.=" 00:00:00";
    $end_time.=" 23:59:00";
    $sql=$db->prepare("INSERT INTO project_info(id,name,str_date,end_date,description,owner) VALUES (?,?,?,?,?,?)");
    $sql->execute([$numberformat,$pro_name,$str_time,$end_time,$description,$owner]);
    for($i=0;$i<count($department);$i++){
        $sqldp=$db->prepare("INSERT INTO project_department(id_project,id_department) VALUES ('$numberformat','$department[$i]')");
        $sqldp->execute();
    }
    $numberpro=$numberformat;
    $numberpro.="001";
    foreach ($process as $prorec){
        $sqlps=$db->prepare("INSERT INTO project_process(id,id_project,name) VALUES ('$numberpro','$numberformat','$prorec->tag')");
        $sqlps->execute();
        $numberpro++;
    }
    echo "Saved";
} catch (PDOException $ex) {
    echo $ex->getLine()." ".$ex->getMessage();
    $db->rollBack();
}
?>

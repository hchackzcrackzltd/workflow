<?php
require_once '../../Tool/condb.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$id= filter_input(INPUT_POST, "idprocess",FILTER_SANITIZE_STRING);
$process= json_decode(filter_var($_REQUEST["addpro"]));
try {
    $datasql=$db->prepare("SELECT MAX(id) AS MX FROM project_process WHERE id_project='$id'");
    $datasql->execute();
    $datacol=$datasql->fetchColumn();
    if(isset($datacol)){
        $numberpro=($datacol)+1;
    }else{
        $numberpro=$id;
        $numberpro.='001';
    }
    foreach ($process as $prorec){
        $sqlps=$db->prepare("INSERT INTO project_process(id,id_project,name) VALUES ('$numberpro','$id','$prorec->tag')");
        $sqlps->execute();
        $numberpro++;
    }
    echo "Saved";
} catch (PDOException $ex) {
    echo $ex->getLine()." ".$ex->getMessage();
    $db->rollBack();
}
?>
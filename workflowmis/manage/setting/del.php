<?php
require_once '../../Tool/condb.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
try {
    $sql=$db->prepare("DELETE FROM project_process WHERE id=?");
    $sql->execute([$id]);
    $sqldel=$db->prepare("DELETE FROM flow_line WHERE point_from=? OR point_to=?");
    $sqldel->execute([$id,$id]);
    $sqldel1=$db->prepare("DELETE FROM offset_pro WHERE id_process=?");
    $sqldel1->execute([$id]);
    $sqldel2=$db->prepare("DELETE FROM pre_process WHERE id_proc=? OR id_pre=?");
    $sqldel2->execute([$id,$id]);
    $sqldel3=$db->prepare("DELETE FROM task WHERE idproc=?");
    $sqldel3->execute([$id]);
    echo "SUC";
} catch (PDOException $ex) {
    echo $ex->getLine()." ".$ex->getMessage();
    $db->rollBack();
}
?>

<?php
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
$sql=[];
$sql[]="DELETE FROM project_info WHERE id=?";
$sql[]="DELETE FROM task WHERE idproj=?";
$sql[]="DELETE FROM project_process WHERE id_project=?";
$sql[]="DELETE FROM project_member WHERE id_proj=?";
$sql[]="DELETE FROM project_department WHERE id_project=?";
$sql[]="DELETE FROM offset_pro WHERE id_project=?";
$sql[]="DELETE FROM flow_line WHERE id_project=?";
try {
foreach ($sql as $value){
$sql1=$db->prepare($value);
$sql1->execute([$id]);
}
$sql2=$db->prepare("DELETE FROM pre_process WHERE id_proc LIKE ?");
$sql2->execute([$id."%"]);
echo "Deleted!";
} catch (PDOException $ex) {
    $db->rollBack();
    echo $ex->getMessage();
}

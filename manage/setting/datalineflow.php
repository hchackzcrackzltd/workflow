<?php
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_GET,"id",FILTER_SANITIZE_STRING);
$data=[];
$sqlline=$db->prepare("SELECT * FROM flow_line WHERE id_project=?");
$sqlline->bindValue(1,$id,PDO::PARAM_STR);
$sqlline->execute();
$linedata=$sqlline->fetchAll(PDO::FETCH_OBJ);
foreach ($linedata as $value) {
  if (isset($value->invert_point)) {
    $data[]=["type"=>(int)$value->line_type,"sou"=>'#'.$value->point_to,"des"=>'#'.$value->point_from];
  }else {
    $data[]=["type"=>(int)$value->line_type,"sou"=>'#'.$value->point_from,"des"=>'#'.$value->point_to];
  }
}
echo json_encode($data);
 ?>

<?php
require_once "../../Tool/condb.php";
$idj=filter_input(INPUT_GET,"idj",FILTER_SANITIZE_STRING);

$sql=[
"SELECT *,SUM((SELECT COUNT(C.complete) FROM task AS C WHERE C.idproc=A.id AND C.complete=100)) AS ct FROM project_process AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id_project=? GROUP BY A.id_department",
"SELECT *,SUM((SELECT COUNT(C.complete) FROM task AS C WHERE C.idproc=A.id AND C.complete<100)) AS ct FROM project_process AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id_project=? GROUP BY A.id_department"
];$datajs=[];$datactcm=[];$datactin=[];$count=1;
foreach ($sql as $value) {
$sqldept=$db->prepare($value);
$sqldept->execute([$idj]);
$datadept=$sqldept->fetchAll(PDO::FETCH_ASSOC);
foreach ($datadept as $value1) {
  if($count){
  if(isset($value1["department"])){
  $datajs[]=$value1["department"];
  $datactcm[]=(int)$value1["ct"];
}
}else{
  if(isset($value1["department"])){
  $datajs[]=$value1["department"];
  $datactin[]=(int)$value1["ct"];
}
}
}$count=0;
}
$comaj=[
  "type"=>"bar",
  "data"=>[
    "labels"=>array_unique($datajs),
    "datasets"=>[[
    "label"=>"Task Complete",
    "borderColor"=>"#def9c8",
    "backgroundColor"=>"#def9c8",
    "data"=>$datactcm],[
      "label"=>"Task Remaining",
      "borderColor"=>"#d2ebf6",
      "backgroundColor"=>"#d2ebf6",
      "data"=>$datactin]]
    ],
    "options"=>[
      "responsive"=>true,
      "tooltips"=>["mode"=>"index","intersect"=>false],
      "scales"=>[
      "xAxes"=>[["stacked"=>true]],
      "yAxes"=>[["stacked"=>true]]
    ]]
  ];
echo json_encode($comaj);
?>

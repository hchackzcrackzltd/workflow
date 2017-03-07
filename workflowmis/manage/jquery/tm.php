<?php
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_POST,"id",FILTER_SANITIZE_STRING);
$sqlstr="SELECT A.name AS name,A.str_date AS date_strp,A.end_date AS date_endp,B.str_date AS date_strj,B.end_date AS date_endj,C.department AS dept ";
$sqlstr.="FROM project_process AS A INNER JOIN project_info AS B ON A.id_project=B.id INNER JOIN department AS C ON A.id_department=C.id ";
$sqlstr.="WHERE A.id_project=?";
$sql1=$db->prepare($sqlstr);
$sql1->execute([$id]);
$sqldata=$sql1->fetchAll(PDO::FETCH_ASSOC);
 ?>
var data = [
<?php foreach ($sqldata as $value) {
  $pss=new DateTime($value["date_strp"]);
  $pse=new DateTime($value["date_endp"]);
?>
{
    "start": new Date("<?= $pss->format("F d, Y H:i:s") ?>"),
    "end": new Date("<?= $pse->format("F d, Y H:i:s") ?>"),
    "content": "<b><?= $value["name"] ?></b>",
    "group":"<b><?= $value["dept"] ?></b>"
},
<?php } ?>
];
var options = {
    width:  "100%",
    height: "auto",
    layout: "box",
    editable: false,
    cluster: true,
    selectable:false,
    showNavigation:true,
    axisOnTop: true,
    <?php
    if(count($sqldata)>0){
    $pjs=new DateTime($sqldata[0]["date_strj"]);
    $pje=new DateTime($sqldata[0]["date_endj"]);
     ?>
    min:new Date("<?= $pjs->format("F d, Y H:i:s") ?>"),
    max:new Date("<?= $pje->format("F d, Y H:i:s") ?>")
    <?php } ?>
};
timeline = new links.Timeline(document.getElementById("timeline"), options);
timeline.draw(data);
function trd() {
  timeline.redraw();
}
window.addEventListener("resize",trd);

<?php
require "../../Tool/condb.php";
require_once "../../logon/checkauth.php";
$sort=filter_input(INPUT_GET,"sort",FILTER_SANITIZE_NUMBER_INT);
 ?>
 <h3><u>Dashboard</u></h3>
 <div class="row">
   <div class="col s1">
     <label>FilterBy:</label>
   </div>
<div class="col s10">
  <div class="input-field">
    <input type="radio" name="sort" id="sort1" <?= ($sort=="0")?"checked":NULL ?> value="0">
    <label for="sort1">All</label>
    <input type="radio" name="sort" id="sort2" <?= ($sort=="1")?"checked":NULL ?> value="1">
    <label for="sort2">Complete</label>
    <input type="radio" name="sort" id="sort3" <?= ($sort=="2")?"checked":NULL ?> value="2">
    <label for="sort3">Progress</label>
</div>
</div>
 </div>
<div class="row">
<?php
$datash=[];
switch ($sort) {
  case "0":
  if($typeuser=="ad"){
    $datash=$db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E ORDER BY E.end_date ASC");
  }else {
    $datash=$db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E INNER JOIN project_member AS F ON F.id_proj=E.id WHERE F.id_user='$iduser' GROUP BY E.name ORDER BY E.end_date ASC");
  }
    break;
  case "1":
  if($typeuser=="ad"){
    foreach ($db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E ORDER BY E.end_date ASC") as $valuepro) {
      (isset($valuepro["per"]))?(round((int)$valuepro["per"])==100)?$datash[]=$valuepro:NULL:NULL;
        }
  }else{
  foreach ($db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E INNER JOIN project_member AS F ON F.id_proj=E.id WHERE F.id_user='$iduser' GROUP BY E.name ORDER BY E.end_date ASC") as $valuepro) {
    (isset($valuepro["per"]))?(round((int)$valuepro["per"])==100)?$datash[]=$valuepro:NULL:NULL;
      }
    }
    break;
  case "2":
  if($typeuser=="ad"){
    foreach ($db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E ORDER BY E.end_date ASC") as $valuepro1) {
      $per=(int)(isset($valuepro1["per"]))?$valuepro1["per"]:0;
      (round($per)<100)?$datash[]=$valuepro1:NULL;
        }
  }else{
    foreach ($db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E INNER JOIN project_member AS F ON F.id_proj=E.id WHERE F.id_user='$iduser' GROUP BY E.name ORDER BY E.end_date ASC") as $valuepro1) {
      $per=(int)(isset($valuepro1["per"]))?$valuepro1["per"]:0;
      (round($per)<100)?$datash[]=$valuepro1:NULL;
        }
  }
    break;
    default:
    if($typeuser=="ad"){
      $datash=$db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E ORDER BY E.end_date ASC");
    }else {
      $datash=$db->query("SELECT *,(SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) FROM project_process AS A WHERE A.id_project=E.id) AS per FROM project_info AS E INNER JOIN project_member AS F ON F.id_proj=E.id WHERE F.id_user='$iduser' GROUP BY E.name ORDER BY E.end_date ASC");
    }
    break;
}
foreach ($datash as $value) {
 ?>
 <div class="col s12 m12 l6">
     <div class="card z-depth-3">
         <div class="card-image waves-effect waves-block waves-light" style="padding:10px">
             <canvas class="canvas" data-idp="<?= $value["id"] ?>"></canvas>
         </div>
         <div class="card-content">
           <a class="btn-floating halfway-fab waves-effect waves-light cyan activator right showfw" data-id="<?= $value["id"] ?>" title="View Flow"><i class="material-icons">pageview</i></a>
           <a class="btn-floating halfway-fab waves-effect waves-light blue right infof" data-id="<?= $value["id"] ?>" title="Info Project"><i class="material-icons">info</i></a>
           <a class="btn-floating halfway-fab waves-effect waves-light light-green right task" data-id="<?= $value["id"] ?>" title="Task"><i class="material-icons">assignment_turned_in</i></a>
           <span class="card-title text-capa"><?= $value["name"] ?></span>
             <div class="progress">
               <div class="determinate <?= (isset($value["per"]))?(round($value["per"])==100)?"green":"blue":"" ?>" style="width: <?= (isset($value["per"]))?round($value["per"]):0 ?>%"></div>
             </div><b>Complete <?= (isset($value["per"]))?round($value["per"]):0 ?>%</b>
             <?php if(new DateTime()>new DateTime($value["end_date"])&&round((int)$value["per"])<100){ ?>
             <ul class="tags">
               <li><p class="tag tag-red"><b>Due Date</b></p></li>
             </ul>
             <?php } ?><br />
         </div>
     </div>
 </div>
<?php
}if(count($datash)==0){
 ?>
 <div class="col s12 center-align">
   <b>No Project</b>
 </div>
 <?php } ?>
</div>

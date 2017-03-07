<?php
require_once "../../Tool/condb.php";
require_once "../../logon/checkauth.php";
$idj=filter_input(INPUT_GET, "id" ,FILTER_SANITIZE_STRING);
 ?>
 <?php
 if($typeuser=="ad"){
   $sqlproj=$db->prepare("SELECT * FROM project_info WHERE id=?");
   $sqlproj->execute([$idj]);
 }else{
   $sqlproj=$db->prepare("SELECT * FROM project_info AS A INNER JOIN project_member AS B ON A.id=B.id_proj WHERE A.id=? AND B.id_user=?");
   $sqlproj->execute([$idj,$iduser]);
 }
 $dataproj=$sqlproj->fetch(PDO::FETCH_OBJ);
  ?>
 <h3 class="text-capa">Project: <?= $dataproj->name ?></h3>
<div class="row">
  <div class="col s12">
    <ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active waves-effect waves-light maincolor maintxt"><i class="material-icons">info outline</i><b>Project Info</b></div>
      <div class="collapsible-body">
        <div class="container">
           <div class="row">
             <div class="col s3 l3">
               <b>Project Name:</b>
             </div>
             <div class="col s9 l9 text-capa">
               <?= $dataproj->name ?>
             </div>
             <div class="col s2">
               <b>Date Start:</b>
             </div>
             <div class="col s4">
                 <?php
                 $dtfs=new DateTime($dataproj->str_date);
                 echo $dtfs->format("d/M/Y");
                   ?>
             </div>
             <div class="col s2">
               <b>Date End:</b>
             </div>
             <div class="col s4">
               <?php
               $dtfd=new DateTime($dataproj->end_date);
               echo $dtfd->format("d/M/Y");
                 ?>
             </div>
             <div class="col s2"><b>Owner:</b></div><div class="col s10"><?= $dataproj->owner ?></div>
             <div class="col s12"><b>Description:</b></div>
             <div class="col s12 text-capa">&nbsp;&nbsp;&nbsp;<?= (isset($dataproj->description))?$dataproj->description:"<b>No Description</b>"; ?></div>
             <div class="col s12"><b>Status:</b></div>
             <div class="col s12">
               <?php
               $sqltask=$db->prepare("SELECT SUM((SELECT AVG(B.complete) FROM task AS B WHERE B.idproc=A.id))/COUNT(A.id) AS com FROM project_process AS A WHERE A.id_project=?");
               $sqltask->execute([$idj]);
               $datataskcm=$sqltask->fetch(PDO::FETCH_OBJ);
                ?>
                <div class="progress">
                  <div class="determinate <?= (isset($datataskcm->com))?(round($datataskcm->com)==100)?"green":"blue":"" ?>" style="width: <?= (isset($datataskcm->com))?round($datataskcm->com):0 ?>%"></div>
                </div>
                <b>Complete: <?= round($datataskcm->com) ?>%</b>
             </div>
           </div>
        </div>
      </div>
    </li>
  </ul>
  </div>
  <?php
  if($typeuser=="ad" || $dataproj->type=="ad"){
  $sqlidp=$db->prepare("SELECT (SELECT AVG(complete) FROM task AS C WHERE C.idproc=A.id) AS com,A.end_date AS dateend,A.name AS name,A.id AS id,B.department AS dept FROM project_process AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id_project=?");
  $sqlidp->execute([$idj]);
  }else{
  $sqlidp=$db->prepare("SELECT (SELECT AVG(complete) FROM task AS C WHERE C.idproc=A.id) AS com,A.end_date AS dateend,A.name AS name,A.id AS id,B.department AS dept FROM project_process AS A LEFT JOIN department AS B ON A.id_department=B.id LEFT JOIN project_member AS F ON F.id_dept=B.id WHERE A.id_project=? AND F.id_user=?");
  $sqlidp->execute([$idj,$iduser]);
  }
  $dataps=$sqlidp->fetchAll(PDO::FETCH_ASSOC);
  $cmp=[];$ncmp=[];
  foreach ($dataps as $value) {
  if(isset($value["dateend"])){
    $pt=(isset($value["com"]))?(int)$value["com"]:0;
    if($pt<100 && (new DateTime("now")>new DateTime($value["dateend"]))){
      $ncmp[]=[$value["name"],$value["dept"],(isset($value["com"]))?(int)$value["com"]:0,$value["id"]];
    }
    if($pt==100){
      $cmp[]=[$value["name"],$value["dept"],$value["com"],$value["id"]];
    }
  }
  }
   ?>
  <div class="col s12">
    <ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active waves-effect waves-light maincolor maintxt"><i class="material-icons">assignment_late</i><b>Over Due Process</b></div>
      <div class="collapsible-body">
        <table class="striped centered <?= (count($ncmp)>0)?"responsive-table":null ?>">
          <thead>
            <tr>
              <th>Process</th>
              <th>Department</th>
              <th>Complete</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($ncmp as $value) {   ?>
            <tr>
              <td class="text-capa"><?= $value[0] ?></td>
              <td class="text-capa"><?= $value[1] ?></td>
              <td><div class="progress">
                <div class="determinate blue" style="width: <?= round($value[2]) ?>%"></div>
              </div>
              <b><?= round($value[2]) ?>%</b></td>
              <td><a data-target="detailtask" data-type="1" data-psid="<?= $value[3] ?>" class="waves-effect waves-light btn blue darken-3 detailtask"><i class="material-icons">description</i></a></td>
            </tr>
            <?php }if(count($ncmp)<1){ ?>
              <tr>
                <td colspan="4"><b>No Task Over Due</b></td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    </li>
  </ul>
  </div>
  <div class="col s12">
    <ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active waves-effect waves-light maincolor maintxt detailtask"><i class="material-icons">done</i><b>Success Process</b></div>
      <div class="collapsible-body">
        <table class="striped centered <?= (count($cmp)>0)?"responsive-table":null ?>">
          <thead>
            <tr>
              <th>Process</th>
              <th>Department</th>
              <th>Complete</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($cmp as $value1) {   ?>
            <tr>
              <td class="text-capa"><?= $value1[0] ?></td>
              <td class="text-capa"><?= $value1[1] ?></td>
              <td><div class="progress">
                <div class="determinate green" style="width: <?= round($value1[2]) ?>%"></div>
              </div>
              <b><?= round($value1[2]) ?>%</b></td>
              <td><a data-target="detailtask" data-type="2" data-psid="<?= $value1[3] ?>" class="waves-effect waves-light btn blue darken-3 detailtask"><i class="material-icons">description</i></a></td>
            </tr>
            <?php }if(count($cmp)<1){ ?>
              <tr>
                <td colspan="4"><b>No Task Success</b></td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    </li>
  </ul>
  </div>
</div>
<div id="detailtask" class="modal">
    <div class="modal-content">
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Close</a>
    </div>
  </div>

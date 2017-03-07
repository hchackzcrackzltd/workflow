<?php
require_once "../../Tool/condb.php";
require_once "../../logon/checkauth.php";
$idj=filter_input(INPUT_GET, "id",FILTER_SANITIZE_STRING);
$status=true;$rc=0;
$sqlmp=$db->prepare("SELECT * FROM project_member WHERE id_user=? AND id_proj=?");
$sqlmp->execute([$iduser,$idj]);
$datamp=$sqlmp->fetch(PDO::FETCH_ASSOC);
if($typeuser=="ad" || $datamp["type"]=="ad"){
  $sqlt="SELECT A.id AS idp,A.name,C.department,(SELECT AVG(complete) FROM task AS B WHERE B.idproc=A.id) AS com";
  $sqlt.=" FROM project_process AS A INNER JOIN department AS C ON C.id=A.id_department";
  $sqlt.=" WHERE A.id_project=?";
  $sql1=$db->prepare($sqlt);
  $sql1->execute([$idj]);
}else{
$sqlt="SELECT A.id AS idp,A.name,C.department,(SELECT AVG(complete) FROM task AS B WHERE B.idproc=A.id) AS com";
$sqlt.=" FROM project_process AS A LEFT JOIN department AS C ON C.id=A.id_department";
$sqlt.=" WHERE A.id_project=? AND (SELECT COUNT(*) FROM project_member AS F WHERE (F.id_dept=A.id_department) AND F.id_user=?)>0";
$sql1=$db->prepare($sqlt);
$sql1->execute([$idj,$iduser]);
}
$sqldata1=$sql1->fetchAll(PDO::FETCH_OBJ);
 ?>
<nav>
    <div class="nav-wrapper" style="background:#9ab93b !important">
      <div class="col s12" style="padding-left:15px">
        <a href="#!" class="breadcrumb task" data-id="<?= $idj ?>"><b>Task</b></a>
      </div>
    </div>
  </nav>
  <ul class="collapsible" data-collapsible="accordion">
      <li>
        <div class="collapsible-header active grey maintxt"><i class="material-icons">assignment turned in</i><b>Process</b></div>
        <div class="collapsible-body">
          <table class="striped centered <?= (count($sqldata1)>0)?"responsive-table":null ?>">
            <thead>
              <tr>
                <th>Process</th>
                <th>Department</th>
                <th>Complete</th>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sqldata1 as $value) {
                /*if($datamp["type"]=="us"){
                $sql2=$db->prepare("SELECT (SELECT AVG(complete) FROM task AS B WHERE B.idproc=A.id_pre) AS com,A.id_pre FROM pre_process AS A WHERE A.id_proc=?");
                $sql2->execute([$value->idp]);
                $sql2data=$sql2->fetchAll(PDO::FETCH_OBJ);
                $buf=[];
                foreach ($sql2data as $value8) {
                  if(($value8->id_pre)=="F"){
                    $status=true;
                 }else{
                   $buf[]=(isset($value8->com))?$value8->com:0;
                if((array_sum($buf)/count($buf))>=100){
                  $status=true;
                }else{
                  $status=false;
                }}}
              }*/
                if($status){
                  $rc++;
                   ?>
                  <tr>
                <td><?= $value->name ?></td>
                <td><?= $value->department ?></td>
                <td>
                  <b><?= (isset($value->com))?round($value->com):0 ?>%</b>
                  <div class="progress">
                    <div class="determinate <?= (round($value->com)==100)?"green":"blue" ?>" style="width: <?= (isset($value->com))?round($value->com):0 ?>%"></div>
                  </div>
                </td>
                <td><a class="waves-effect waves-light blue btn addtk" title="Add Task" data-idp="<?= $value->idp ?>" data-idj="<?= $idj ?>"><i class="material-icons">assignment_returned</i></a></td>
              </tr>
                <?php }}if($rc==0){ ?>
                  <tr>
                    <td colspan="4"><b>No Process</b></td>
                  </tr>
                  <?php } ?>
            </tbody>
          </table>
        </div>
      </li>
    </ul>

<?php
require_once "../../Tool/condb.php";
$idj=filter_input(INPUT_GET, "idj",FILTER_SANITIZE_STRING);
$idp=filter_input(INPUT_GET, "idp",FILTER_SANITIZE_STRING);
$sqlproc=$db->prepare("SELECT B.department AS dept,A.str_date AS std,A.end_date AS edd,A.name AS name FROM project_process AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id=? AND A.id_project=?");
$sqlproc->bindValue(1,$idp,PDO::PARAM_STR);
$sqlproc->bindValue(2,$idj,PDO::PARAM_STR);
$sqlproc->execute();
$sqldata=$sqlproc->fetch(PDO::FETCH_OBJ);
 ?>
<h4 class="text-capa"><?= $sqldata->name ?></h4>
<ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active grey lighten-2"><i class="material-icons">view_list</i>Detail</div>
      <div class="collapsible-body">
        <div class="container">
        <div class="row">
          <div class="col s2">
            <b>Start Date:</b>
          </div>
          <div class="col s4">
            <?php $strdatef=new DateTime($sqldata->std);
            echo $strdatef->format("d/M/Y"); ?>
          </div>
          <div class="col s2">
            <b>Due Date:</b>
          </div>
          <div class="col s4">
            <?php $enddatef=new DateTime($sqldata->edd);
            echo $enddatef->format("d/M/Y"); ?>
          </div>
        </div>
        <div class="row">
          <div class="col s3">
            <b>Department:</b>
          </div>
          <div class="col s9">
            &nbsp;&nbsp;<?= $sqldata->dept ?>
          </div>
        </div>
        </div>
      </div>
    </li>
    <li>
      <?php
      $sqltask=$db->prepare("SELECT * FROM task WHERE idproj=? AND idproc=? ORDER BY priority ASC");
      $sqltask->execute([$idj,$idp]);
      $sqldata=$sqltask->fetchAll(PDO::FETCH_OBJ);
        ?>
      <div class="collapsible-header grey lighten-2 active"><i class="material-icons">assignment</i> <span class="new badge" data-badge-caption="Task"><?= count($sqldata) ?></span>Task</div>
      <div class="collapsible-body">
          <table class="striped centered <?= (count($sqldata)>0)?"responsive-table":null ?>">
            <thead>
              <tr>
                <th>Task</th>
                <th>Owner</th>
                <th>Due Date</th>
                <th>Complete</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($sqldata as $value) {
               ?>
              <tr>
                <td><?= $value->name ?></td>
                <td><?= $value->ownner ?></td>
                <td><?php
                $duef=new DateTime($value->enddate);
                echo $duef->format("d/M/Y");
                 ?></td>
                <td>
                  <div class="progress">
                    <div class="determinate <?= (isset($value->complete))?(round($value->complete)==100)?"green":"blue":"" ?>" style="width: <?= (isset($value->complete))?round($value->complete):0 ?>%"></div>
                  </div>
                  <b><?= $value->complete ?>%</b>
                </td>
              </tr>
              <?php }
              if(count($sqldata)<1){
                ?>
                <tr>
                  <td colspan="4"><b>No Tasks</b></td>
                </tr>
            <?php
              }
               ?>
            </tbody>
          </table>
      </div>
    </li>
  </ul>

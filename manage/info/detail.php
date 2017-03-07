<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
$id=filter_input(INPUT_GET, "id" ,FILTER_SANITIZE_STRING);
$type=filter_input(INPUT_GET, "type" ,FILTER_SANITIZE_STRING);
$sqltask=$db->prepare("SELECT * FROM project_process WHERE id=?");
$sqltask->execute([$id]);
$datatask=$sqltask->fetch(PDO::FETCH_OBJ);
$sqltaska=$db->prepare(($type=="1")?"SELECT * FROM task WHERE idproc=?":"SELECT * FROM task WHERE idproc=? AND complete=100");
$sqltaska->execute([$id]);
$datataska=$sqltaska->fetchAll(PDO::FETCH_ASSOC);
function fdate($tim){
  $date=new DateTime($tim);
  return $date->format("d/M/Y");
}
 ?>
<ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active grey lighten-2"><i class="material-icons">view_list</i>Detail</div>
      <div class="collapsible-body">
        <div class="container">
          <div class="row">
            <div class="col s3">
              <b>Process: </b>
            </div>
            <div class="col s9 text-capa">
              <?= $datatask->name ?>
            </div>
            <div class="col s2">
              <b>Start Date: </b>
            </div>
            <div class="col s4 text-capa">
              <?= fdate($datatask->str_date) ?>
            </div>
            <div class="col s2">
              <b>Due Date: </b>
            </div>
            <div class="col s4 text-capa">
              <?= fdate($datatask->end_date) ?>
            </div>
          </div>
        </div>
      </div>
    </li>
    <li>
        <div class="collapsible-header active grey lighten-2"><i class="material-icons">assignment</i> <span class="new badge" data-badge-caption="Task"><?= count($datataska) ?></span>Task</div>
      <div class="collapsible-body">
        <table class="striped centered <?= (count($datataska)>0)?"responsive-table":null ?>">
          <thead>
            <tr>
              <th>Task</th><th>Date Start</th><th>Due Date</th><th>Ownner</th><th>Complete</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($datataska as $value) {
             ?>
            <tr>
              <td><?= $value["name"] ?></td>
              <td><?= fdate($value["strdate"]) ?></td>
              <td><?= fdate($value["enddate"]) ?></td>
              <td>
                <?php
                $sss=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$value["ownner"]."))",["displayName"]);
                $sse=ldap_get_entries($ds, $sss);
                echo (count($sse)>1)?$sse[0]["displayname"][0]:$value["ownner"];
                 ?>
              </td>
              <td>
                <div class="progress">
              <div class="determinate <?= (isset($value["complete"]))?(round($value["complete"])==100)?"green":"blue":"" ?>" style="width: <?= (isset($value["complete"]))?round($value["complete"]):0 ?>%"></div>
            </div><b>Complete <?= $value["complete"] ?>%</b>
              </td>
            </tr>
            <?php }if(count($datataska)<1){ ?>
              <tr>
                <td colspan="5"><b>No Task</b></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </li>
  </ul>

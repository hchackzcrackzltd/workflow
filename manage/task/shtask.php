<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
require_once "../../logon/checkauth.php";
$idp=filter_input(INPUT_GET, "id",FILTER_SANITIZE_STRING);
$idj=filter_input(INPUT_GET, "idj",FILTER_SANITIZE_STRING);
function dateform($date,$fmt)
{
  $da=new DateTime($date);
  return $da->format($fmt);
}
$sql1=$db->prepare("SELECT * FROM project_process WHERE id=? AND id_project=?");
$sql1->execute([$idp,$idj]);
$sql1data=$sql1->fetch(PDO::FETCH_OBJ);
$sql2=$db->prepare("SELECT * FROM project_member WHERE id_proj=? AND id_user=?");
$sql2->execute([$idj,$iduser]);
$sql2data=$sql2->fetch(PDO::FETCH_OBJ);
 ?>
<nav>
    <div class="nav-wrapper" style="background:#9ab93b !important">
      <div class="col s12" style="padding-left:15px">
        <a href="#!" class="breadcrumb task" data-id="<?= $idj ?>">Task</a>
        <a href="#!" class="breadcrumb addtk"  data-idp="<?= $idp ?>" data-idj="<?= $idj ?>"><b><?= $sql1data->name ?></b></a>
      </div>
    </div>
  </nav><br>
  <?php
  if($typeuser=="ad"||$sql2data->type=="ad"){ ?>
  <div class="row">
    <div class="col s12">
      <a class="waves-effect waves-light btn green addnwtk" title="Add Task" data-proc="<?= $idp ?>" data-target="modal"><i class="material-icons left">add</i>Task</a>
    </div>
  </div>
  <?php } ?>
  <ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active grey maintxt"><i class="material-icons">info</i><b>Process Info</b></div>
      <div class="collapsible-body">
        <div class="container">
        <div class="row">
          <div class="col s3">
            <b>Process: </b>
          </div>
          <div class="col s9">
            <?= $sql1data->name ?>
          </div>
          <div class="col s3">
            <b>Start Date:</b>
          </div>
          <div class="col s3">
            <?= dateform($sql1data->str_date,"d/M/Y") ?>
          </div>
          <div class="col s3">
            <b>Due Date:</b>
          </div>
          <div class="col s3">
            <?= dateform($sql1data->end_date,"d/M/Y") ?>
          </div>
        </div>
        </div>
      </div>
    </li>
      <li>
        <div class="collapsible-header active grey maintxt"><i class="material-icons">assignment_turned_in</i><b>Task</b></div>
        <div class="collapsible-body">
          <?php
          $sqlshtk=$db->prepare("SELECT * FROM task WHERE idproj=? AND idproc=? ORDER BY priority ASC");
          $sqlshtk->execute([$idj,$idp]);
          $datashtk=$sqlshtk->fetchAll(PDO::FETCH_ASSOC);
           ?>
          <table class="striped centered <?= (count($datashtk)>0)?"responsive-table":null ?>">
            <thead>
              <tr>
                <th>Task</th>
                <th>Date Start</th>
                <th>Due Date</th>
                <th>Ownner</th>
                <th>Complete</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($datashtk as $value) {
               ?>
              <tr>
                <td><?= $value["name"] ?></td>
                <td><?= dateform($value["strdate"],"d/M/Y") ?></td>
                <td><?= dateform($value["enddate"],"d/M/Y") ?></td>
                <td>
                  <?php
                  $sss=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$value["ownner"]."))",["displayName"]);
                  $sse=ldap_get_entries($ds, $sss);
                  echo (count($sse)>1)?$sse[0]["displayname"][0]:$value["ownner"];
                   ?>
                </td>
                <td>
                  <b><?= (isset($value["complete"]))?round($value["complete"]):0 ?>%</b>
                  <div class="progress">
                    <div class="determinate <?=(round($value["complete"])==100)?"green":"blue" ?>" style="width: <?= (isset($value["complete"]))?round($value["complete"]):0 ?>%"></div>
                  </div>
                </td>
                <td><a class="waves-effect waves-light waves-yellow btn-flat uptk" data-type="1" data-target="modal2" data-id="<?= $value["id"] ?>" data-psid="<?= $value["idproc"] ?>">
                  <i class="material-icons">edit</i>
                </a>
              </td>
                <td><a class="waves-effect waves-light waves-red btn-flat deltk" data-psid="<?= $value["idproc"] ?>" data-id="<?= $value["id"] ?>" data-idj="<?= $value["idproj"] ?>"
                  <?= ($iduser==$value["ownner"]||$typeuser=="ad"||$sql2data->type=="ad")?null:"disabled" ?>>
                  <i class="material-icons">delete</i>
                </a>
              </td>
              </tr>
              <?php
            }if(count($datashtk)<1){
               ?>
               <tr>
                 <td colspan="7"><b>No Task</b></td>
               </tr>
               <?php } ?>
            </tbody>
          </table>
        </div>
      </li>
    </ul>
    <?php
    if($typeuser=="ad"||$sql2data->type=="ad"){ ?>
    <div id="modal" class="modal">
        <div class="modal-content">
    <ul class="collapsible" data-collapsible="expandable">
      <li>
      <div class="collapsible-header active grey lighten-2"><i class="material-icons">add</i><b>Add Task</b></div>
      <div class="collapsible-body">
        <form name="addtask" data-idp="<?= $idp ?>" data-idj="<?= $idj ?>">
          <input type="text" name="idp" value="<?= $idp ?>" hidden>
          <input type="text" name="idj" value="<?= $idj ?>" hidden>
        <div class="container">
          <div class="row">
            <div class="col s12 input-field">
              <input type="text" name="name" id="name" minlength="3" required>
              <label for="name" data-error="Please specify more 3 word" data-success="right">Subject</label>
            </div>
            </div>
            <div class="row">
            <div class="col s6 input-field">
              <input type="text" id="str_date" name="str_date" class="datepicker" data-stps="<?= $sql1data->str_date ?>" value="<?= dateform($sql1data->str_date,"Y-m-d") ?>" required>
              <label for="str_date" class="active">Start Date</label>
            </div>
            <div class="col s6 input-field">
              <input type="text" id="end_date" name="end_date" class="datepicker" data-edps="<?= $sql1data->end_date ?>" value="<?= dateform($sql1data->str_date,"Y-m-d") ?>" required>
              <label for="end_date" class="active">Due Date</label>
            </div>
            </div>
            <div class="row hide">
            <div class="col s12 input-field">
              <input type="text" id="reminder" name="reminder" class="datepicker" required>
              <label for="reminder">Reminder</label>
            </div>
            </div>
            <div class="row">
            <div class="col s12 input-field">
              <select name="owner" id="ownerps" required>
                <option value="0" selected disabled>Please Select Owner</option>
                <?php foreach ($db->query("SELECT * FROM project_member WHERE id_dept IN ('".$sql1data->id_department."','101') AND id_proj='".$idj."'") as $ownlist) {
                  $dss=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$ownlist["id_user"]."))",["displayName"]);
                  $dse=ldap_get_entries($ds, $dss);
                   ?>
                <option value="<?= $ownlist["id_user"] ?>"><?= (count($dse)>1)?$dse[0]["displayname"][0]:$ownlist["id_user"] ?></option>
                <?php } ?>
              </select>
              <label for="ownerps" class="active">Owner</label>
            </div>
            </div>
            <div class="row">
            <div class="col s12 input-field">
              <select name="assgn[]" id="assgnps" multiple required>
                <option value="0" selected disabled>Please Select To Assign Task</option>
                <?php foreach ($db->query("SELECT * FROM project_member WHERE id_dept IN ('".$sql1data->id_department."','101') AND id_proj='".$idj."'") as $asslist) {
                  $ass=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$asslist["id_user"]."))",["displayName"]);
                  $ase=ldap_get_entries($ds, $ass);
                   ?>
                <option value="<?= $asslist["id_user"] ?>"><?= (count($ase)>1)?$ase[0]["displayname"][0]:$asslist["id_user"] ?></option>
                <?php } ?>
              </select>
              <label for="assgnps" class="active">Assign</label>
            </div>
            </div>
            <div class="row">
            <div class="col s12 input-field">
              <textarea name="description" id="description" minlength="3" class="materialize-textarea" required></textarea>
              <label for="description" data-error="Please specify more 3 word" data-success="right">Description</label>
            </div>
            </div>
            <div class="row">
            <div class="col s12 center-align">
              <button type="submit" class="btn green wave-light"><i class="material-icons left">save</i>Save</button>
              <button type="reset" class="btn modal-action modal-close red wave-light"><i class="material-icons left">replay</i>Reset</button>
            </div>
            </div>
        </div>
      </form>
      </div>
    </li>
  </ul>
</div>
</div>
<?php } ?>
<div id="modal2" class="modal">
<div class="modal-content" id="uptask">
</div>
</div>

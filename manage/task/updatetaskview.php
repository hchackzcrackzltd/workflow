<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
require_once "../../logon/checkauth.php";
$idc=filter_input(INPUT_GET, "idc",FILTER_SANITIZE_STRING);
$num=filter_input(INPUT_GET, "num",FILTER_SANITIZE_STRING);
$typeu=filter_input(INPUT_GET, "typeu",FILTER_SANITIZE_STRING);
$sql1=$db->prepare("SELECT * FROM task WHERE idproc=? AND id=?");
$sql1->execute([$idc,$num]);
$data=$sql1->fetch(PDO::FETCH_OBJ);
$sql2=$db->prepare("SELECT COUNT(*) AS ct FROM task WHERE idproc=?");
$sql2->execute([$idc]);
$data2=$sql2->fetch(PDO::FETCH_OBJ);
$sql3=$db->prepare("SELECT * FROM task_at WHERE id_proc=? AND id_task=?");
$sql3->execute([$idc,$num]);
$data3=$sql3->fetchAll(PDO::FETCH_OBJ);
$sql4=$db->prepare("SELECT * FROM project_process WHERE id=?");
$sql4->execute([$idc]);
$sql1data=$sql4->fetch(PDO::FETCH_OBJ);
$sql5=$db->prepare("SELECT * FROM project_member WHERE id_proj=? AND id_user=?");
$sql5->execute([$data->idproj,$iduser]);
$sql2data=$sql5->fetch(PDO::FETCH_OBJ);
$sql6=$db->prepare("SELECT * FROM assign_task WHERE idproc=? AND idtask=? AND user=?");
$sql6->execute([$idc,$num,$iduser]);
$sql3data=$sql6->fetch(PDO::FETCH_OBJ);
 ?>
<ul class="collapsible" data-collapsible="expandable">
<li>
<div class="collapsible-header active grey lighten-2"><i class="material-icons">edit</i><b>Update Task</b></div>
<div class="collapsible-body">
<form name="uptask" data-idp="<?= $data->idproc ?>" data-idj="<?= $data->idproj ?>" data-type="<?= $typeu ?>">
<input type="text" name="idp" value="<?= $data->idproc ?>" hidden>
<input type="text" name="idt" value="<?= $data->id ?>" hidden>
<div class="container">
<div class="row">
  <div class="col s12 input-field">
    <input type="text" name="name" id="nameu" minlength="3" value="<?= $data->name ?>" required  <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?null:"readonly" ?>>
    <label class="active" for="nameu" data-error="Please specify more 3 word" data-success="right">Subject</label>
  </div>
  </div>
  <div class="row">
  <div class="col s6 input-field">
    <input type="text" id="str_dateu" name="str_date" class="<?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?"datepicker":null ?>" value="<?= $data->strdate ?>" data-stps="<?= $data2->str_date ?>" required <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?null:"readonly" ?>>
    <label class="active" for="str_dateu">Start Date</label>
  </div>
  <div class="col s6 input-field">
    <input type="text" id="end_dateu" name="end_date" class="<?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?"datepicker":null ?>" value="<?= $data->enddate ?>" data-edps="<?= $data2->end_date ?>" required <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?null:"readonly" ?>>
    <label class="active" for="end_dateu">Due Date</label>
  </div>
  </div>
  <div class="row hide">
  <div class="col s12 input-field">
    <input type="text" id="reminderu" name="reminder"  value="<?= $data->reminder ?>" class="datepicker" required <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?null:"readonly" ?>>
    <label class="active" for="reminderu">Reminder</label>
  </div>
  </div>
  <div class="row">
  <div class="col s6 input-field">
    <input type="number" name="complete" id="completeu" value="<?= $data->complete ?>" min="0" max="100" <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad"||$sql3data->user==$iduser)?null:"readonly" ?>>
    <label class="active" for="completeu">%Complete</label>
  </div>
  <div class="col s6 input-field">
    <input type="number" name="priority" id="priorityu" value="<?= $data->priority ?>" min="0" max="<?= $data2->ct ?>" <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?null:"readonly" ?>>
    <label class="active" for="priorityu">Priority</label>
  </div>
  </div>
  <div class="row <?= ($typeuser=="ad"||$sql2data->type=="ad")?null:"hide" ?>">
  <div class="col s12 input-field">
      <select name="owner" id="ownerpsu" required>
        <option value="0" selected disabled>Please Select Owner</option>
        <?php foreach ($db->query("SELECT * FROM project_member WHERE id_dept IN ('".$sql1data->id_department."','101') AND id_proj='".$data->idproj."'") as $ownlist) {
          $dss=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$ownlist["id_user"]."))",["displayName"]);
          $dse=ldap_get_entries($ds, $dss);
           ?>
        <option value="<?= $ownlist["id_user"] ?>" <?= ($ownlist["id_user"]==$data->ownner)?"selected":null ?>><?= (count($dse)>1)?$dse[0]["displayname"][0]:$ownlist["id_user"] ?></option>
        <?php } ?>
        >
      </select>
      <label class="active" for="ownerpsu">Owner</label>
  </div>
  </div>
  <div class="row <?= ($typeuser=="ad"||$sql2data->type=="ad")?"hide":null ?>">
  <div class="col s12 input-field">
      <select id="ownerpsus" required disabled>
        <option value="0" selected disabled>Please Select Owner</option>
        <?php foreach ($db->query("SELECT * FROM project_member WHERE id_dept IN ('".$sql1data->id_department."','101') AND id_proj='".$data->idproj."'") as $ownlist) {
          $dss=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$ownlist["id_user"]."))",["displayName"]);
          $dse=ldap_get_entries($ds, $dss);
           ?>
        <option value="<?= $ownlist["id_user"] ?>" <?= ($ownlist["id_user"]==$data->ownner)?"selected":null ?>><?= (count($dse)>1)?$dse[0]["displayname"][0]:$ownlist["id_user"] ?></option>
        <?php } ?>
        >
      </select>
      <label class="active" for="ownerpsus">Owner</label>
  </div>
  </div>
  <div class="row <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?null:"hide" ?>">
  <div class="col s12 input-field">
    <select name="assgn[]" id="assgnpsu" multiple required>
      <option value="0" selected disabled>Please Select To Assign Task</option>
      <?php foreach ($db->query("SELECT * FROM project_member WHERE id_dept IN ('".$sql1data->id_department."','101') AND id_proj='".$sql1data->id_project."'") as $asslist) {
        $ass=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$asslist["id_user"]."))",["displayName"]);
        $ase=ldap_get_entries($ds, $ass);
        $sqlcka=$db->prepare("SELECT * FROM assign_task WHERE idtask=? AND idproc=? AND user=?");
        $sqlcka->execute([$num,$idc,$asslist["id_user"]]);
        $sqlckd=$sqlcka->fetch(PDO::FETCH_OBJ);
         ?>
      <option value="<?= $asslist["id_user"] ?>" <?= isset($sqlckd->user)?"selected":null ?>><?= (count($ase)>1)?$ase[0]["displayname"][0]:$asslist["id_user"] ?></option>
      <?php } ?>
      >
    </select>
    <label for="assgnpsu" class="active">Assign</label>
  </div>
  </div>
  <div class="row <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad")?"hide":null ?>">
  <div class="col s12 input-field">
    <select id="assgnpsus" multiple required disabled>
      <option value="0" selected disabled>Please Select To Assign Task</option>
      <?php foreach ($db->query("SELECT * FROM project_member WHERE id_dept IN ('".$sql1data->id_department."','101') AND id_proj='".$sql1data->id_project."'") as $asslist) {
        $ass=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=person)(sAMAccountName=".$asslist["id_user"]."))",["displayName"]);
        $ase=ldap_get_entries($ds, $ass);
        $sqlcka=$db->prepare("SELECT * FROM assign_task WHERE idtask=? AND idproc=? AND user=?");
        $sqlcka->execute([$num,$idc,$asslist["id_user"]]);
        $sqlckd=$sqlcka->fetch(PDO::FETCH_OBJ);
         ?>
      <option value="<?= $asslist["id_user"] ?>" <?= isset($sqlckd->user)?"selected":null ?>><?= (count($ase)>1)?$ase[0]["displayname"][0]:$asslist["id_user"] ?></option>
      <?php } ?>
      >
    </select>
    <label for="assgnpsus" class="active">Assign</label>
  </div>
  </div>
  <div class="row">
  <div class="col s12 input-field">
    <textarea name="description" id="descriptionu" minlength="3" class="materialize-textarea" required <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad"||$sql3data->user==$iduser)?null:"readonly" ?>><?= $data->detail ?></textarea>
    <label class="active" for="descriptionu" data-error="Please specify more 3 word" data-success="right">Description</label>
  </div>
  </div>
  <div class="row">
  <div class="col s12 input-field file-field">
    <div class="btn blue">
      <span><i class="material-icons">attach_file</i></span>
      <input type="file" name="fileat[]" multiple <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad"||$sql3data->user==$iduser)?null:"disabled" ?>>
    </div>
    <div class="file-path-wrapper">
      <input class="file-path validate" type="text" placeholder="Attatch File">
    </div>
  </div>
  </div>
  <div class="row">
  <div class="col s12"><span class="new badge blue" data-badge-caption="รองรับไฟล์"></span><span class="new badge orange" data-badge-caption="JPEG"></span><span class="new badge amber" data-badge-caption="PDF"></span>
    <table class="table striped centered <?= (count($data3)<>0)?"responsive-table":null ?>">
      <thead>
        <tr>
          <th>File</th>
          <th>Size</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($data3 as $valuef) { ?>
        <tr>
          <td><a href="attatchment/<?= $valuef->filer ?>" target="_blank"><?= $valuef->filen ?></a></td>
          <td><?= round(filesize("../../attatchment/".$valuef->filer)/1048576,2) ?>MB</td>
          <td><button class="delat btn waves-effect waves-light red" data-file="<?= $valuef->filer ?>" title="Delete"><i class="material-icons">delete</i></button></td>
        </tr>
      <?php }if(count($data3)==0){ ?>
        <tr>
          <td colspan="3"><b>No Attatch File</b></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  </div>
  <div class="row">
  <div class="col s12 center-align"><br>
    <button type="submit" class="btn green wave-light" <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad"||$sql3data->user==$iduser)?null:"disabled" ?>><i class="material-icons left">save</i>Save</button>
    <button type="reset" class="btn modal-action modal-close red wave-light" <?= ($iduser==$data->ownner||$typeuser=="ad"||$sql2data->type=="ad"||$sql3data->user==$iduser)?null:"disabled" ?>><i class="material-icons left">replay</i>Reset</button>
  </div>
  </div>
</div>
</form>
</div>
</li>
</ul>

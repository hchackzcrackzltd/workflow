<?php
require_once '../../Tool/condb.php';
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
$sqldata = $db->prepare("SELECT * FROM project_info WHERE id='$id'");
$sqldata->execute();
$proname = $sqldata->fetch(PDO::FETCH_OBJ);
$dateps=new DateTime($proname->str_date);
$datepe=new DateTime($proname->end_date);
?>
<h3 style="text-transform: capitalize">Setting: <?= $proname->name ?></h3>
<div class="row">
    <div class="col s12">
        <button class="btn waves-effect waves-light green" data-target="addpro"><i class="material-icons left">add</i>Add Process</button>
        <button class="btn waves-effect waves-light blue deplunch" data-target="adddep" data-idj="<?= $proname->id ?>"><i class="material-icons left">group</i>Manage Department</button>
    </div>
</div>
<ul class="collapsible popout" data-collapsible="accordion">
  <li>
      <div class="collapsible-header waves-effect maincolor maintxt" style="font-weight: 600;font-size: 1.04em"><i class="material-icons">edit</i>Edit Info Project</div>
      <div class="collapsible-body">
        <form class="editproj">
          <input type="text" name="idj" value="<?= $proname->id ?>" hidden readonly>
          <div class="container">
            <div class="row">
                <div class="col s10 offset-s1 input-field">
                    <input type="text" id="project_nameed" value="<?= $proname->name ?>" name="project_name" class="validate" minlength="3" maxlength="100" required/>
                    <label for="project_name" data-error="Please Specify Project Name More Then 3 Word" data-success="Right">Project Name</label>
                </div>
            </div>
            <div class="row">
              <div class="col s5 offset-s1 input-field">
                <input disabled type="date" name="str_date" id="str_date" class="datepicker" data-str="<?= $dateps->format("Y-m-d") ?>" value="<?= $dateps->format("Y-m-d") ?>">
                <label class="active">Start Date</label>
              </div>
              <div class="col s5 input-field">
                <input type="date" name="due_date" id="due_date" class="datepicker" data-due="<?= $datepe->format("Y-m-d") ?>" value="<?= $datepe->format("Y-m-d") ?>">
                <label class="active">Due Date</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s10 offset-s1">
                <input type="text" value="<?= $proname->owner ?>" name="owner" id="ownered" required>
                <label for="owner">Owner</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s10 offset-s1">
                <textarea id="Descriptioned" name="Description" class="materialize-textarea validate" minlength="3" maxlength="100" required><?= $proname->description ?></textarea>
                <label for="Description" data-error="Please Specify Description More Then 3 Word" data-success="Right">Description</label>
              </div>
            </div>
            <div class="row">
                <div class="col s12 center-align">
                    <button type="submit" class="btn waves-effect waves-light green"><i class="material-icons left">save</i>Save</button>
                    <button type="reset" class="btn waves-effect waves-light red"><i class="material-icons left">replay</i>Reset</button>
                </div></div>
          </div>
        </form>
      </div>
    </li>
    <li>
        <div class="collapsible-header waves-effect waves-light maincolor maintxt" style="font-weight: 600;font-size: 1.04em"><i class="material-icons left">assignment</i>Process Setting</div>
        <div class="collapsible-body">
<ul class="collapsible popout">
    <?php
    function checkse($db, $id, $id2) {
        $datasql = $db->prepare("SELECT * FROM pre_process WHERE id_proc='$id' AND id_pre='$id2'");
        $datasql->execute();
        $data = $datasql->fetch(PDO::FETCH_OBJ);
        if (isset($data->id_proc)) {
            return "selected";
        } else {
            return "";
        }
    }
    function chetimepro($db,$id,$type){
        $datasql = $db->prepare("SELECT * FROM project_info WHERE id='$id'");
        $datasql->execute();
        $data = $datasql->fetch(PDO::FETCH_OBJ);
        return ($type==1)? $data->str_date : $data->end_date;
    }
    foreach ($db->query("SELECT * FROM project_process WHERE id_project='$id'") as $data) {
        $id_pm = $data["id"];
        $strfm=new DateTime(isset($data['str_date'])?$data['str_date']:$proname->str_date);
        $endfm=new DateTime(isset($data['end_date'])?$data['end_date']:$proname->end_date);
        ?>
        <li>
            <div class="collapsible-header waves-effect grey maintxt"><i class="material-icons left">toc</i><i class="material-icons right del" data-del="<?= $id_pm ?>" title="Delete">delete</i><h5 style="text-transform: capitalize"><?= $data['name'] ?></h5></div>
            <div class="collapsible-body">
                <form class="settingfm" data-idpj="<?= $id_pm ?>">
                    <div class="row">
                        <div class="col s10 offset-s1 input-field">
                            <input type="text" id="project_name" name="process_name" value="<?= $data['name'] ?>" class="validate" minlength="3" maxlength="100" required/>
                            <label for="project_name" class="active" data-error="Please Specify Project Name More Then 3 Word" data-success="Right">Process Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s5 offset-s1 input-field">
                            <input type="text" class="datepicker st<?= $id_pm ?>" id="str_time" name="str_proc" minlength="1" data-stpro="<?= chetimepro($db,$id,1) ?>" value="<?= $strfm->format("Y-m-d") ?>" required>
                            <label for="str_proc" data-error="Please Specify Date Start" data-success="Right">Date Start</label>
                        </div>
                        <div class="col s5 input-field">
                            <input type="text" class="datepicker et<?= $id_pm ?>" id="str_end" name="end_proc" minlength="1" data-edpro="<?= chetimepro($db,$id,2) ?>" value="<?= $endfm->format("Y-m-d") ?>" required>
                            <label for="str_proc" data-error="Please Specify Date End" data-success="Right">Date End</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s10 offset-s1 input-field">
                            <select name="department" required>
                                <option disabled selected value="0">Plase Select Department</option>
                                <?php
                                foreach ($db->query("SELECT b.department AS depart,a.id_department AS id FROM project_department AS a LEFT JOIN department AS b ON a.id_department=b.id WHERE a.id_project='$id'") as $dep) {
                                    ?>
                                <option value="<?= $dep["id"] ?>" <?= ($data["id_department"]==$dep["id"])?"selected":"" ?>><?= $dep["depart"] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <label>Department</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s10 offset-s1 input-field">
                            <select name="pre_proc[]" multiple="multiple" required>
                                <option disabled selected value="0">Plase Select Process</option>
                                <option value="F">Start</option>
                                <?php
                                foreach ($db->query("SELECT * FROM project_process WHERE id_project='$id' AND id!='$id_pm'") as $pro_data) {
                                    ?>
                                    <option <?= checkse($db, $id_pm, $pro_data["id"]) ?> value="<?= $pro_data["id"] ?>"><?= $pro_data["name"] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <label>Pre-Process</label>
                            <input type="text" value="<?= $id_pm ?>" name="idprocess" hidden/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 center-align">
                            <button type="submit" class="btn waves-effect waves-light green"><i class="material-icons left">done</i>Save</button>
                        </div></div>
                </form>
            </div>
        </li>
        <?php
    }
    ?>
</ul>
        </div>
    </li>
</ul>
<!-- setflow --->
<h3 style="text-transform: capitalize">Setting Flow</h3>
<div class="row" id="flowpreview"></div>
<!-- setflow --->
<div id="addpro" class="modal bottom-sheet">
    <form name="addprof" data-idpf="<?= $id ?>">
    <div class="modal-content">
        <h4><i class="material-icons left">add</i>Add Process</h4>
      <div class="row">
          <div class="col s10 offset-s1">
              <div class="chips chips-placeholder addproc"></div>
              <input type="text" name="addpro" hidden/>
              <input type="text" name="idprocess" value="<?= $id ?>" hidden/>
          </div>
      </div>
    </div>
    <div class="modal-footer">
        <button class=" modal-action modal-close waves-effect waves-green btn-flat" type="submit"><i class="material-icons left">add</i>ADD</button>
    </div>
    </form>
  </div>
  <div id="adddep" class="modal">
    <div class="modal-content adddep">
    </div>
  </div>
  <!-- modal flowsetting -->
  <div id="flowsetting" class="modal flowsetting modal-fixed-footer" style="overflow:hidden">
    <div class="modal-content flowsetting">
    </div>
  </div>
  <!-- modal flowsetting -->

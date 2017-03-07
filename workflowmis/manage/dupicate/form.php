<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
$idj=filter_input(INPUT_GET, "id",FILTER_SANITIZE_STRING);
$sqlpj=$db->prepare("SELECT id,name FROM project_info WHERE id=?");
$sqlpj->execute([$idj]);
$datapj=$sqlpj->fetch(PDO::FETCH_ASSOC);
 ?>
<div class="modal-content">
  <form class="duppro">
      <h4>Duplicate Project: <?= $datapj["name"] ?></h4>
      <ul class="collapsible" data-collapsible="expandable">
          <li>
              <div class="collapsible-header waves-effect waves-ddd maincolor maintxt active"><i class="material-icons">info</i><h5>New Project</h5></div>
              <div class="collapsible-body">
                <div class="row hide">
                    <div class="col s10 offset-s1">
                        <input type="text" id="idproject" name="idproject" value="<?= $datapj["id"] ?>" readonly/>
                    </div>
                </div>
                  <div class="row">
                      <div class="col s10 offset-s1 input-field">
                          <input type="text" id="project_name" name="project_named" class="validate" minlength="3" maxlength="100" required/>
                          <label for="project_name" data-error="Please Specify Project Name More Then 3 Word" data-success="Right">Project Name</label>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col s5 offset-s1 input-field">
                          <input type="text" class="datepicker" id="str_time" name="str_timed" minlength="1" required>
                          <label for="str_time" data-error="Please Specify Date Start" data-success="Right">Date Start</label>
                      </div>
                      <div class="col s5 input-field">
                          <input type="text" class="datepicker" id="str_end" name="str_endd" minlength="1" required>
                          <label for="str_end" data-error="Please Specify Date End" data-success="Right">Date End</label>
                      </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s10 offset-s1">
                      <input type="text" name="ownerd" id="ownerdp" required>
                      <label for="owner">Owner</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s10 offset-s1">
                      <textarea id="Description" name="Descriptiond" class="materialize-textarea validate" minlength="3" maxlength="100"  required></textarea>
                      <label for="Description" data-error="Please Specify Description More Then 3 Word" data-success="Right">Description</label>
                    </div>
                  </div>
              </div>
          </li>
      </ul>
      <div class="row">
          <div class="col s12 center-align">
              <button type="submit" class="btn waves-effect waves-light green"><i class="material-icons left">done</i>Apply</button>
              <button type="reset" class="btn waves-effect waves-light red"><i class="material-icons left">replay</i>Reset</button>
          </div></div>
  </form>
    </div>

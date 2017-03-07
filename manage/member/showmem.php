<?php
require_once "../../Tool/conldap.php";
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_GET, "id",FILTER_SANITIZE_STRING);
$sqlproj=$db->prepare("SELECT * FROM project_info WHERE id=?");
$sqlproj->execute([$id]);
$dataproj=$sqlproj->fetch(PDO::FETCH_OBJ);
 ?>
<h3 class="text-capa">Member: <?= $dataproj->name ?></h3>
<div class="row">
  <div class="col s6">
    <ul class="collapsible" data-collapsible="expandable">
        <li>
          <div class="collapsible-header active maincolor maintxt"><i class="material-icons">group</i><b>Select Department</b></div>
          <div class="collapsible-body">
             <div class="container">
               <div class="row">
                 <div class="col s12 input-field">
                   <select id="department1" required>
                     <option value="0" selected disabled>Plase Select Department</option>
                     <?php
                     foreach ($db->query("SELECT * FROM department WHERE id!='101'") as $value) {
                      ?>
                     <option value="<?= $value["id"] ?>"><?= $value["department"] ?></option>
                     <?php } ?>
                   </select>
                   <label>Department</label>
                 </div>
               </div>
             </div>
          </div>
        </li>
        <li>
          <div class="collapsible-header active grey maintxt"><i class="material-icons">person outline</i><b>User</b></div>
          <div class="collapsible-body">
            <div class="container">
              <table  class="striped centered responsive-table">
                <tbody class="droptrueallm dop">
                  <tr>
                    <td><b>Plase Select Department</b></td>
                  </tr>
                </tbody>
              </table><br />
            </div>
          </div>
        </li>
      </ul>
  </div>
  <div class="col s6">
    <ul class="collapsible" data-collapsible="expandable">
        <li>
          <div class="collapsible-header active maincolor maintxt"><i class="material-icons">group</i><b>Select Department Project</b></div>
          <div class="collapsible-body">
            <?php
            $sqldept=$db->prepare("SELECT A.id_department AS iddp,B.department AS name FROM project_department AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id_project=?");
             $sqldept->execute([$id]);
             $datadept=$sqldept->fetchAll(PDO::FETCH_OBJ);
             ?>
             <div class="container">
               <div class="row">
                 <div class="col s12 input-field">
                   <select name="department" id="deptpro">
                     <option value="0" selected disabled>Plase Select Department</option>
                     <?php foreach ($datadept as $value) { ?>
                     <option value="<?= $value->iddp ?>"><?= $value->name ?></option>
                     <?php } ?>
                   </select>
                   <label>Department</label>
                 </div>
               </div>
             </div>
          </div>
        </li>
        <li>
          <div class="collapsible-header active grey maintxt"><i class="material-icons">person</i><b>User</b></div>
          <div class="collapsible-body">
            <div class="container center-align">
              <table  class="striped centered responsive-table">
                <tbody class="dropuserp userusem" data-proid="<?= $id ?>">
                  <tr class="ui-state-disabled">
                    <td><b>Please Select Department</b></td>
                  </tr>
                </tbody>
              </table><br/>
            </div>
          </div>
        </li>
        <li>
          <div class="collapsible-header active grey maintxt"><i class="material-icons">person_outline</i><b>Administrator</b></div>
          <div class="collapsible-body">
            <div class="container center-align">
              <table  class="striped centered responsive-table">
                <tbody class="dropadminp adminusem" data-proid="<?= $id ?>">
                  <tr class="ui-state-disabled">
                    <td><b>Please Select Department</b></td>
                  </tr>
                </tbody>
              </table><br/>
            </div>
          </div>
        </li>
        <li>
          <div class="collapsible-header active grey maintxt"><i class="material-icons">delete</i><b>Delete</b></div>
          <div class="collapsible-body">
            <table  class="striped centered responsive-table">
              <tbody class="dropdelm">
                <tr class="ui-state-disabled"><td><b>Drop To Delete</b></td></tr>
              </tbody>
            </table>
          </div>
        </li>
      </ul>
  </div>
</div>

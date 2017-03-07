<?php
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_GET, "idj",FILTER_SANITIZE_STRING);
 ?>
<ul class="collapsible" data-collapsible="accordion">
    <li>
      <div class="collapsible-header active grey lighten-2"><i class="material-icons">group</i><b>Manage Department Project</b></div>
      <div class="collapsible-body">
        <div class="container">
          <div class="row">
            <div class="col s12 m9 input-field">
              <select name="adddepnew" id="adddepla" required>
                <option value="0" selected disabled>Please Select Department</option>
                <?php foreach ($db->query("SELECT * FROM department WHERE id<>100 AND id<>101") as $valuedepnw) { ?>
                <option value="<?= $valuedepnw["id"] ?>"><?= $valuedepnw["department"] ?></option>
                <?php } ?>
              </select>
              <label for="adddepla">Department</label>
            </div>
            <div class="col s12 m3 center-align"><br>
              <a class="waves-effect waves-light green btn addnwdep" data-idj="<?= $id ?>"><i class="material-icons left">add</i>Add</a>
            </div>
          </div>
          <div class="row">
            <div class="col s12">
              <table class="striped centered responsive-table">
                <thead>
                  <tr>
                    <th>Department</th>
                    <th width="20%"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($db->query("SELECT * FROM project_department AS B LEFT JOIN department AS A ON A.id=B.id_department WHERE B.id_project='$id'") as $value) { ?>
                    <tr>
                      <td><?= $value["department"] ?></td>
                      <td><button class="waves-effect waves-red btn-flat delnwdep" type="button" data-idj="<?= $value["id_project"] ?>" data-idd="<?= $value["id_department"] ?>"><i class="material-icons">delete</i></button></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </li>
  </ul>

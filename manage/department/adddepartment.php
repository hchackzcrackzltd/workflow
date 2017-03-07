<?php
require_once "../../Tool/condb.php";
 ?>
<h3>Department Master</h3>
<ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header active maincolor maintxt"><i class="material-icons">add</i><b>Add Department</b></div>
      <div class="collapsible-body">
        <div class="container">
        <div class="row">
          <form name="adddepartmentf">
        <div class="col s9 input-field">
          <input type="text" name="departmentadd" required minlength="2"/>
          <label for="departmentadd" data-error="Please Specify Department More 2 Word" data-success="right">Department</label>
        </div>
        <div class="col s3"><br>
          <button type="submit" class="waves-effect waves-green green btn"><i class="material-icons left">add</i>Add</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </li>
    <li>
      <div class="collapsible-header active grey maintxt"><i class="material-icons">domain</i><b>Department</b></div>
      <div class="collapsible-body">
        <table class="striped centered responsive-table">
          <thead>
            <tr>
              <th>Department</th>
              <th width="20%"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($db->query("SELECT * FROM department WHERE id!='100'") as $value) { ?>
            <tr>
              <td><?= $value["department"] ?></td>
              <td><a class="waves-effect waves-red btn-flat deldept" data-idd="<?= $value["id"] ?>"><i class="material-icons">delete</i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </li>
  </ul>

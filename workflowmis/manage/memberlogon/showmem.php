<h3>Manage User</h3>
<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
 ?>
<div class="row">
  <div class="col s6">
    <ul class="collapsible" data-collapsible="expandable">
        <li>
          <div class="collapsible-header active maincolor maintxt"><i class="material-icons">group</i><b>Select Department For User</b></div>
          <div class="collapsible-body">
            <?php
             $data=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=group)(!(cn=Spare&Temp))(!(cn=RemoteServerAllowedGroup)))",["dn"]);
             $endata=ldap_get_entries($ds, $data);
             ?>
             <div class="container">
               <div class="row">
                 <div class="col s12 input-field">
                   <select id="departmentlog" required>
                     <option value="0" selected disabled>Please Select Department</option>
                     <?php
                     for ($i=0; $i <$endata["count"] ; $i++) {
                      ?>
                     <option value="<?= substr(strstr(explode(",", $endata[$i]["dn"])[1],"="),1) ?>"><?= substr(strstr(explode(",", $endata[$i]["dn"])[1],"="),1) ?></option>
                     <?php } ?>
                   </select>
                   <label for="department1">Department</label>
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
                <tbody class="droptrue dop">
                  <tr class="ui-state-disabled">
                    <td><b>Please Select Department</b></td>
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
            $sqldept=$db->prepare("SELECT id AS iddp,department AS name FROM department");
             $sqldept->execute();
             $datadept=$sqldept->fetchAll(PDO::FETCH_OBJ);
             ?>
             <div class="container">
               <div class="row">
                 <div class="col s12 input-field">
                   <select name="department" id="depmemlogon" required>
                     <option value="0" selected disabled>Please Select Department</option>
                     <?php foreach ($datadept as $value) { ?>
                     <option value="<?= $value->iddp ?>"><?= $value->name ?></option>
                     <?php } ?>
                   </select>
                   <label for="depmemlogon">Department</label>
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
                <tbody class="droptrue2 useruse">
                  <tr class="ui-state-disabled">
                    <td><b>Please Select Department</b></td>
                  </tr>
                </tbody>
              </table><br/>
            </div>
          </div>
        </li>
        <li>
          <div class="collapsible-header active grey maintxt"><i class="material-icons">person_outline</i><b>Administartor</b></div>
          <div class="collapsible-body">
            <div class="container center-align">
              <table  class="striped centered responsive-table">
                <tbody class="droptrue3 adminuse">
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
              <tbody class="dropdelmem">
                <tr class="ui-state-disabled"><td><b>Drop To Delete</b></td></tr>
              </tbody>
            </table>
          </div>
        </li>
      </ul>
  </div>
</div>

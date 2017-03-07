<?php
require_once '../../Tool/condb.php';
?>
<form class="setup">
    <h3>Create Project</h3>
    <ul class="collapsible" data-collapsible="expandable">
        <li>
            <div class="collapsible-header waves-effect waves-ddd maincolor maintxt active"><i class="material-icons">info</i><h5>New Project</h5></div>
            <div class="collapsible-body">
                <div class="row">
                    <div class="col s10 offset-s1 input-field">
                        <input type="text" id="project_name" name="project_name" class="validate" minlength="3" maxlength="100" required/>
                        <label for="project_name" data-error="Please Specify Project Name More Then 3 Word" data-success="Right">Project Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s5 offset-s1 input-field">
                        <input type="text" class="datepicker" id="str_time" name="str_time" minlength="1" required>
                        <label for="str_time" data-error="Please Specify Date Start" data-success="Right">Date Start</label>
                    </div>
                    <div class="col s5 input-field">
                        <input type="text" class="datepicker" id="str_end" name="str_end" minlength="1" required>
                        <label for="str_end" data-error="Please Specify Date End" data-success="Right">Date End</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s10 offset-s1 input-field">
                        <select name="department" multiple required>
                            <option disabled selected value="0">Plase Select Department</option>
                            <?php
                            foreach ($db->query("SELECT * FROM department WHERE id<>100 AND id<>101") as $dep) {
                                ?>
                                <option value="<?= $dep["id"] ?>"><?= $dep["department"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <label>Department</label>
                        <input name="departvalue" type="text" hidden/>
                    </div>
                </div>
                <div class="row">
                  <div class="input-field col s10 offset-s1">
                    <input type="text" name="owner" id="owner" required>
                    <label for="owner">Owner</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s10 offset-s1">
                    <textarea id="Description" name="Description" class="materialize-textarea validate" minlength="3" maxlength="100"  required></textarea>
                    <label for="Description" data-error="Please Specify Description More Then 3 Word" data-success="Right">Description</label>
                  </div>
                </div>
            </div>
        </li>
        <li>
            <div class="collapsible-header waves-effect waves-ddd maincolor maintxt active"><i class="material-icons">work</i><h5>Process</h5></div>
            <div class="collapsible-body">
                <div class="row">
                    <div class="col s10 offset-s1">
                        <div class="chips chips-placeholder"></div>
                        <input type="text" name="datachip" hidden readonly/>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="row">
        <div class="col s12 center-align">
            <button type="submit" class="btn waves-effect waves-light green"><i class="material-icons left">done</i>Setup</button>
            <button type="reset" class="btn waves-effect waves-light red"><i class="material-icons left">replay</i>Reset</button>
        </div></div>
</form>

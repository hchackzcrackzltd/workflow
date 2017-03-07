<li><a href="#!" class="waves-effect waves-green dashboard"><i class="material-icons">dashboard</i>DashBoard</a></li>
<li><div class="divider"></div></li>
<li><a class="subheader">My Task</a></li>
<li><a href="#!" class="waves-effect waves-green mytask"><i class="material-icons">assignment_turned_in</i>My Task</a></li>
<?php if($typeuser=="ad"){ ?>
  <li><div class="divider"></div></li>
<li><a class="subheader">Create Project</a></li>
<li><a href="#!" class="waves-effect waves-green addproject"><i class="material-icons">add box</i>Create Project</a></li>
<?php } ?>
<li><div class="divider"></div></li>
<li><a class="subheader">Project List</a></li>
<li class="no-padding">
    <ul class="collapsible collapsible-accordion">
        <?php
        $sqlmenu=($typeuser=="ad")?"SELECT * FROM project_info":"SELECT * FROM project_member AS A INNER JOIN project_info AS B ON A.id_proj=B.id WHERE A.id_user='".$iduser."' GROUP BY B.name ASC";
        foreach ($db->query($sqlmenu) as $proj) {
            ?>
            <li>
                <a class="collapsible-header waves-effect waves-ddd truncate"><i class="material-icons left">assignment</i><?= $proj["name"] ?></a>
                <div class="collapsible-body">
                    <ul>
                      <li><a href="#!" class="waves-effect waves-ddd showfw" data-id="<?= $proj["id"] ?>"><i class="material-icons">pageview</i>View Flow</a></li>
                        <li><a href="#!" class="waves-effect waves-ddd task" data-id="<?= $proj["id"] ?>"><i class="material-icons">content_paste</i>Task</a></li>
                        <li><a href="#!" class="waves-effect waves-ddd infof" data-id="<?= $proj["id"] ?>"><i class="material-icons">info_outline</i>Project Info</a></li>
                        <?php if($typeuser=="ad"||$proj["type"]=="ad"){ ?>
                        <?php if($typeuser=="ad"){ ?>
                        <li><a href="#!" class="waves-effect waves-ddd delproj" data-id="<?= $proj["id"] ?>"><i class="material-icons">delete</i>Delete</a></li>
                        <li><a href="#!" class="waves-effect waves-ddd dup" data-target="dup" data-id="<?= $proj["id"] ?>"><i class="material-icons">content_copy</i>Duplicate</a></li>
                        <?php } ?>
                        <li><a href="#!" class="waves-effect waves-ddd setting" data-id="<?= $proj["id"] ?>"><i class="material-icons">settings</i>Setting</a></li>
                        <li><a href="#!" class="waves-effect waves-ddd member"  data-id="<?= $proj["id"] ?>"><i class="material-icons">person</i>Member</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php } ?>
    </ul>
</li>
<?php if($typeuser=="ad"){ ?>
<li><div class="divider"></div></li>
<li><a class="subheader">Manage User</a></li>
<li><a href="#!" class="waves-effect waves-green memlogon"><i class="material-icons">people</i>Manage User</a></li>
<li><div class="divider"></div></li>
<li><a class="subheader">Department Master</a></li>
<li><a href="#!" class="waves-effect waves-green departadd"><i class="material-icons">domain</i>Department Master</a></li>
  <?php } ?>

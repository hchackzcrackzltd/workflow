<?php
require_once "../../Tool/condb.php";
require_once "../../Tool/conldap.php";
require_once "../../logon/checkauth.php";
 ?>
 <h3>My Task</h3>
 <ul class="collapsible" data-collapsible="accordion">
    <li>
      <div class="collapsible-header waves-effect waves-light active grey maintxt"><i class="material-icons">assignment_turned_in</i><b>Task</b></div>
      <div class="collapsible-body">
        <div class="row">
          <div class="col s12">
            <?php
            $sqltk=$db->prepare("SELECT D.name AS proj,B.name AS pron,B.id,B.idproc,B.complete,B.idproj,B.ownner,A.type AS idj,G.name AS nbp FROM project_member AS A INNER JOIN task AS B ON A.id_proj=B.idproj LEFT JOIN project_info AS D ON D.id=A.id_proj LEFT JOIN project_process AS G ON B.idproc=G.id WHERE A.id_user=? AND (B.ownner=? OR ((SELECT COUNT(*) FROM assign_task AS E WHERE E.idtask=B.id AND E.user=? AND E.idproc=B.idproc)>0))");
            $sqltk->execute([$iduser,$iduser,$iduser]);
            $datatk=$sqltk->fetchAll(PDO::FETCH_OBJ);
             ?>
            <table class="striped centered <?= (count($datatk)>0)?"responsive-table":null ?>">
              <thead>
                <tr>
                  <th>Project</th><th>Process</th><th>Task</th><th></th><th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($datatk as $value) {
                 ?>
                <tr>
                  <td><?= $value->proj ?></td>
                  <td><?= $value->nbp ?></td>
                  <td><?= $value->pron ?></td>
                  <td>
                    <div class="progress">
                      <div class="determinate <?= ($value->complete==100)?"green":"blue" ?>" style="width: <?= $value->complete ?>%"></div>
                    </div><b>Complete <?= $value->complete ?>%</b>
                  </td>
                  <td>
                    <a class="waves-effect waves-green btn-flat dropdown-button" title="Status" data-activates="<?= $value->id.$value->idproc ?>"><i class="material-icons">done</i></a>
                    <ul id='<?= $value->id.$value->idproc ?>' class='dropdown-content'>
                      <li><a class="quiuptk" data-per="50" data-id="<?= $value->id ?>" data-psid="<?= $value->idproc ?>">InProgress</a></li>
                      <li class="divider"></li>
                      <li><a class="quiuptk" data-per="100" data-id="<?= $value->id ?>" data-psid="<?= $value->idproc ?>">Complete</a></li>
                    </ul>
                  </td>
                  <td>
                    <?php if($iduser==$value->ownner||$typeuser=="ad"||$value->type=="ad"){ ?>
                    <a class="waves-effect waves-green btn-flat dropdown-button" title="Edit" data-activates="<?= $value->id.$value->idproc ?>e"><i class="material-icons">mode_edit</i></a>
                    <ul id='<?= $value->id.$value->idproc ?>e' class='dropdown-content'>
                      <li><a class="uptk" data-type="2" data-target="modal2" data-id="<?= $value->id ?>" data-psid="<?= $value->idproc ?>">Edit</a></li>
                      <li class="divider"></li>
                      <li><a class="deltk" data-type="2" data-psid="<?= $value->idproc ?>" data-id="<?= $value->id ?>" data-idj="<?= $value->idj ?>">Delete</a></li>
                    </ul>
                    <?php }else{ ?>
                      <a class="waves-effect waves-green btn-flat uptk" title="Edit" data-target="modal2" data-type="2" data-id="<?= $value->id ?>" data-psid="<?= $value->idproc ?>"><i class="material-icons">mode_edit</i></a>
                    <?php } ?>
                  </td>
                </tr>
                <?php }if(count($datatk)==0){ ?>
                  <tr>
                    <td colspan="5"><b>No Task</b></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </li>
  </ul>
    <div id="modal2" class="modal">
    <div class="modal-content" id="uptask">
    </div>
    </div>

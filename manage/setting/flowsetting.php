<?php
require_once '../../Tool/condb.php';
$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);
$idp = filter_input(INPUT_POST, "idp", FILTER_SANITIZE_STRING);
?>
<form class="relation">
<div class="modal-content">
<div class="row">
    <div class="col s12 m12 l12">
      <?php
      $sqlnamepro=$db->prepare("SELECT name FROM project_process WHERE id=?");
       $sqlnamepro->execute([$id]);
       $dataname=$sqlnamepro->fetch(PDO::FETCH_ASSOC);
       ?>
        <i class="material-icons">timeline</i>&nbsp;&nbsp;<b>Process: <?= $dataname["name"] ?></b>
              <input type="text" name="idp" value="<?= $idp ?>" class="hide" readonly/>
              <input type="text" name="pointform" value="<?= $id ?>" class="hide" readonly/>
            <div class="row" id="showrelation"></div>
            <div class="row"><div class="col s8 m8 l8 offset-l1 offset-m1 offset-s1 input-field">
                    <select name="pointto" required>
                        <option value="0" disabled selected>Please Select Point To</option>
                        <?php
                        foreach ($db->query("SELECT * FROM project_process WHERE id_project='$idp' AND id!='$id'") as $data){
                        ?>
                        <option value="<?= $data['id'] ?>"><?= $data['name'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label>Point To</label>
                </div>
                <div class="col s2 m2 l2">
                    <input type="checkbox" name="invert" value="1" id="invert"/>
                    <label for="invert">Invert Point</label>
                </div>
        </div>
            <div class="row">
                <div class="col s10 m10 l10 offset-l1 offset-m1 offset-s1 input-field">
                    <select name="offset" required>
                        <option value="0" disabled selected>Please Select Offset</option>
                        <?php
                        $sqlckof=$db->prepare("SELECT * FROM offset_pro WHERE id_project=? AND id_process=?");
                        $sqlckof->bindValue(1,$idp,PDO::PARAM_STR);
                        $sqlckof->bindValue(2,$id,PDO::PARAM_STR);
                        $sqlckof->execute();
                        $datackdata=$sqlckof->fetch(PDO::FETCH_OBJ);
                       for($i=0;$i<13;$i++){
                        ?>
                        <option <?= (isset($datackdata->offset))?($datackdata->offset==$i)?"selected":"":""; ?> value="<?= $i ?>">Offset-<?= $i ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label>Offset</label>
                </div>
            </div>
            <div class="row">
                <div class="col l10 m10 s10 offset-l1 offset-m1 offset-s1 input-field">
                    <select name="linetype" required>
                        <option value="0" disabled>Please Select Line Type</option>
                        <option value="1" selected>Type-1</option>
                        <option value="2">Type-2</option>
                        <option value="3">Type-3</option>
                        <option value="4">Type-4</option>
                    </select>
                    <label>Line Type</label>
                </div>
            </div>
            <div class="container">
              <div class="row">
                <div class="col s12">
                  <table class="centered highlight" style="text-transform: capitalize">
                    <thead>
                      <tr>
                        <th>Point to</th><th>Line Type</th><th>Invert Line</th><th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sqlshline=$db->prepare("SELECT A.id AS ID,B.name AS name,A.line_type AS line_t,A.invert_point AS intp FROM flow_line AS A LEFT JOIN project_process AS B ON A.point_to=B.id WHERE A.point_from=? AND A.id_project=?");
                      $sqlshline->execute([$id,$idp]);
                      $datashline=$sqlshline->fetchAll(PDO::FETCH_OBJ);
                      foreach ($datashline as $valueline) {
                       ?>
                      <tr>
                        <td><?= $valueline->name ?></td>
                        <td>Type-<?= $valueline->line_t ?></td>
                        <td><?= (isset($valueline->intp))?"<i class='material-icons'>done</i>":"" ?></td>
                        <td><button type="button" class="btn waves-effect waves-light red lighten-1 delpro" title="Delete Line" data-pointto="<?= $valueline->ID ?>"><i class='material-icons'>delete</i></button></td>
                      </tr>
                      <?php
                    }
                    if(count($datashline)==0){ ?>
                       <td colspan="4"><b>No Relation</b></td>
                       <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
    </div>
</div>
</div>
<div class="modal-footer">
  <div class="row">
      <div class="col s12 m12 l12 center-align"><button class="modal-action modal-close btn waves-effect waves-light green" type="submit"><i class="material-icons left">save</i>Save</button></div>
  </div>
</div>
</form>

<?php
require_once '../../Tool/condb.php';
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
?>
<div class="col s12" id="canvas">
        <?php
        foreach ($db->query("SELECT B.department AS department,A.id_department AS ID FROM project_department AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id_project='$id'") as $data){
        ?>
        <div class="box_first row">
            <?php
               foreach ($db->query("SELECT B.id,B.id_project,B.id_department,B.name,B.str_date,B.end_date,(SELECT A.offset FROM offset_pro AS A WHERE A.id_process=B.id) AS off FROM project_process AS B WHERE B.id_project='$id' AND B.id_department='".$data["ID"]."'") AS $dp){
                 $off=(isset($dp["off"]))?$dp["off"]:0;
                 for($i=0;$i<$off;$i++){
                 ?>
            <div class="col s1">&nbsp;</div>
            <?php } ?>
            <div class="col s1 blockp" id="<?= $dp['id'] ?>" data-target="flowsetting" data-idprocess="<?= $dp['id'] ?>" data-idproject="<?= $id ?>"><article class="ps-active"><p><?= $dp['name'] ?></p></article></div>
            <?php
               }
            ?>
        </div>
        <div id="myDiv"><span><?= $data['department'] ?></span></div>
        <hr/>
        <?php
        }
        ?>
    </div>

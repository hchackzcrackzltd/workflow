<?php
require_once '../../Tool/condb.php';
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
$pjsql=$db->prepare("SELECT * FROM project_info WHERE id=?");
$pjsql->bindValue(1,$id,PDO::PARAM_STR);
$pjsql->execute();
$datapj=$pjsql->fetch(PDO::FETCH_OBJ);
?>
<h3 style="text-transform: capitalize">Project: <?= $datapj->name ?></h3>
<div class="row">
  <div class="col s12" id="timeline">
  </div>
<div class="col s12" id="canvas">
        <?php
        foreach ($db->query("SELECT B.department AS department,A.id_department AS ID FROM project_department AS A LEFT JOIN department AS B ON A.id_department=B.id WHERE A.id_project='$id'") as $data){
        ?>
        <div class="box_first row">
            <?php
               foreach ($db->query("SELECT B.id,B.id_project,B.id_department,B.name,B.str_date,B.end_date,(SELECT A.offset FROM offset_pro AS A WHERE A.id_process=B.id) AS off FROM project_process AS B WHERE B.id_project='$id' AND B.id_department='".$data["ID"]."'") AS $dp){
                 $statuscompre=false;$dateexp=false;
                 $dateend=new DateTime($dp['end_date']);
                 $datenow=new DateTime();
                 $sqlpre=$db->prepare("SELECT (SELECT AVG(complete) AS com FROM task AS A WHERE A.idproc=B.id_pre) AS com,B.id_pre AS ip FROM pre_process AS B WHERE B.id_proc=?");
                 $sqlpre->execute([$dp['id']]);
                 $datapre=$sqlpre->fetchAll(PDO::FETCH_OBJ);
                 $buf=[];
                 foreach ($datapre as $value9) {
                   $buf[]=(isset($value9->com))?$value9->com:0;
                   ($value9->ip=="F")?$statuscompre=true:((array_sum($buf)/count($buf))>=100)?$statuscompre=true:$statuscompre=false;
                 }
                   $sqltaskmn=$db->prepare("SELECT AVG(complete) AS com FROM task WHERE idproj=? AND idproc=?");
                   $sqltaskmn->execute([$id,$dp['id']]);
                   $datataskmn=$sqltaskmn->fetch(PDO::FETCH_OBJ);
                   $com=round((int)(isset($datataskmn->com))?$datataskmn->com:0);
               if($dateend<$datenow){
                 $dateexp=true;
               }
               $off=(isset($dp["off"]))?$dp["off"]:0;
               for($i=0;$i<$off;$i++){ ?>
            <div class="col s1">&nbsp;</div>
            <?php } ?>
            <div class="col s1 detail" id="<?= $dp['id'] ?>" data-target="detailshfw" data-idprocess="<?= $dp['id'] ?>" data-idproject="<?= $id ?>">
              <article <?= ($statuscompre)?"class='ps-active'":"" ?>>
                <p class="<?= ($dateexp&&$com<>100)?"bgcom-error":"" ?> <?= (!$dateexp&&$com<100)?"bgcom-active":"" ?> <?= ($com==100)?"bgcom-complete":"" ?>" style="background-size:100% <?= ($statuscompre)?$com:0 ?>%">
                  <b><?= $dp['name'] ?></b>
                  <?php if($com<100&&$com>0&&$statuscompre){ ?>
                  <span class='bege <?= ($statuscompre&&$dateexp&&$com<100)?"bege-error":"" ?> <?= ($statuscompre&&!$dateexp&&$com<100)?"bege-active":"" ?> <?= ($statuscompre&&$com==100)?"bege-complete":"" ?>'><b>Complete <?= ($statuscompre)?$com:0 ?>%</b></span>
                  <?php } ?>
                </p>
              </article>
            </div>
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
  </div>
  <div id="detailshfw" class="modal">
    <div class="modal-content">
    </div>
    <div class="modal-footer">
      <a href="#!" class=" modal-action modal-close waves-effect waves-red btn-flat">Close</a>
    </div>
  </div>

<?php
require_once "../../Tool/condb.php";
 $fm=filter_input(INPUT_POST,"fm",FILTER_SANITIZE_STRING);
 try {
   chmod("../../attatchment/".$fm, 0777);
   if(unlink("../../attatchment/".$fm)){
     $sql=$db->prepare("DELETE FROM task_at WHERE filer=?");
     $sql->execute([$fm]);
    echo "Success";
  }
 } catch (PDOException $e) {
   $db->rollBack();
   echo $e->getMessage();
 }
 ?>

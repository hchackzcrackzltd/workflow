<?php
require_once "../../Tool/condb.php";
$id=filter_input(INPUT_POST, "id",FILTER_SANITIZE_STRING);
try {
  foreach ($db->query("SELECT MAX(id) AS id FROM department") as $value) {
      $idnw=($value["id"])+1;
  }
  $sql=$db->prepare("INSERT INTO department(id,department) VALUES (?,?)");
  $sql->execute([$idnw,$id]);
} catch (PDOException $e) {
  $db->rollBack();
}
 ?>

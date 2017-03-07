<?php
session_start();
require_once '../Tool/condb.php';
require_once '../Tool/conldap.php';
error_reporting(0);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$username= filter_input(INPUT_POST, "username",FILTER_SANITIZE_STRING);
$password= filter_input(INPUT_POST, "password",FILTER_SANITIZE_STRING);
try {
    $sql=$db->prepare("SELECT * FROM user_logon WHERE id_user=?");
    $sql->bindValue(1,$username,PDO::PARAM_STR);
    $sql->execute();
    $data=$sql->fetch(PDO::FETCH_OBJ);
    if(isset($data->id_user)){
      $snut=ldap_search($ds, "OU=DoDayDream,DC=dodaydream,DC=local", "(&(objectClass=user)(objectCategory=person)(samaccountname=".$data->id_user.")(!(useraccountcontrol=514)))",["samaccountname","dn"]);
      $infout = ldap_get_entries($ds, $snut);
      if(isset($infout[0]["dn"])){
      if(ldap_bind($ds,$infout[0]["dn"],$password)){
          $_SESSION["iduser"]=$infout[0]["samaccountname"][0];
          header("Location: ../app.php");
      }else{
              header("Location: ../index.php");
              $_SESSION["error"]=ldap_error($ds);
      }
      }else{
          header("Location: ../index.php");
          $_SESSION["error"]=ldap_error($ds);
      }
      ldap_close($ds);
    }
    else{
        header("Location: ../index.php");
        $_SESSION["error"]="Please Check Username Or Password";
    }
} catch (PDOException $ex) {
    $db->rollBack();
    print $ex->getLine()." ".$ex->getMessage();
}
?>

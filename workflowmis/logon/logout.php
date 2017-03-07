<?php
session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
unset($_SESSION['iduser']);
session_destroy();
header("Location: ../index.php");
?>
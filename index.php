<?php
session_start();
//$_SESSION['iduser']=filter_var($_SERVER["PHP_AUTH_USER"],FILTER_SANITIZE_STRING);
if(isset($_SESSION['iduser'])){
    header("Location: app.php");
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title>Workflow Management Logon</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link href="Tool/animate.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="Tool/login.css"/>
        <script src="Tool/jquery-3.1.1.min.js"></script>
    </head>
    <body>
        <div class="login-page">
            <div class="form animated zoomInUp">
                <img src="Tool/image/logo.png" width="50%" height="30%" class="animated zoomIn" style="animation-delay: .48s;"/>
                <h3 class="animated fadeInDown" style="animation-delay: .48s;color: #339900">Workflow Management System</h3>
                <form class="login-form" action="logon/logon_check.php" method="POST">
                    <input type="text" name="username" placeholder="รหัสพนักงาน" class="animated fadeInDown" style="animation-delay: .48s;"/>
                    <input type="password" name="password" placeholder="รหัสผ่าน" class="animated fadeInDown" style="animation-delay: .48s;"/>
                    <input value="เข้าสู่ระบบ" type="submit" id="sb" class="animated fadeInDown" style="animation-delay: .48s;"/>
                </form>
            </div>
        </div>
        <script>
        <?php
        if(isset($_SESSION["error"])){
            echo "alert('กรุณาตรวจสอบ Username หรือ Password ของท่าน');";
        }
        unset($_SESSION["error"]);
        ?>
    </script>
    </body>
</html>

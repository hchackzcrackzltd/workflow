<?php
require_once './Tool/condb.php';
require_once './Tool/conldap.php';
require_once './logon/checkauth.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0,target-densitydpi=device-dpi, user-scalable=no"/>
        <title>Workflow Management</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link href="Tool/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css"/>
        <link href="Tool/materialize/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="Tool/jqueryui/jquery-ui.theme.min.css"/>
        <link rel="stylesheet" href="Tool/tm/timeline.css"/>
        <link href="newstyle.less" type="text/css" rel="stylesheet/less"/>
        <script src="Tool/jquery-3.1.1.min.js"></script>
        <script src="Tool/less.min.js"></script>
        <script src="Tool/materialize/js/materialize.min.js"></script>
        <script src="Tool/sweetalert-master/dist/sweetalert.min.js"></script>
        <script src="Tool/moment-with-locales.js"></script>
        <script src="Tool/yui-min.js"></script>
        <script src="Tool/jqueryui/jquery-ui.min.js"></script>
        <script src="Tool/Chart.min.js"></script>
        <script src="Tool/jquery.ui.touch-punch.min.js"></script>
        <script src="Tool/tm/timeline-min.js"></script>
    </head>
    <body>
    <header>
      <div class="navbar-fixed nav_top">
            <nav>
                <div class="nav-wrapper maincolor">
                    <a href="#" class="brand-logo right">DoDayDream</a>
                    <ul id="nav-mobile" class="left">
                        <li><a class="btn-floating btn-large waves-effect waves-light btnmain sidebar button-collapse show-on-small" data-activates="slide-out"><i class="material-icons">reorder</i></a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <ul id="slide-out" class="side-nav fixed">
            <li><div class="userView">
                    <div class="background">
                        <img src="image/green.jpg">
                    </div>
                    <?php if($typeuser=="ad"){ ?>
                    <a href="#!user" class="center-align"><img class="circle" src="image/ad.png"></a>
                    <?php }else{ ?>
                    <a href="#!user" class="center-align"><img class="circle" src="image/us.png"></a>
                    <?php } ?>
                    <a href="#!name"><span class="white-text name"><?= $info[0]["displayname"][0] ?> - <?= $texttype ?></span></a>
                    <a href="#!email"><span class="white-text email"><?= $info[0]["mail"][0] ?></span></a>
                    <a href="logon/logout.php" title="Logout"><span class="white-text email"><i class="material-icons left">open_in_new</i><b>Logout</b></span></a>
                </div></li>
            <?php
            require_once './manage/menu/menu.php';
            ?>
        </ul>
        </header>
        <main>
        <div class="container"><br>
          <div class="row">
            <div class="col s12 center-align">
              <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue">
                  <div class="circle-clipper left">
                    <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                    <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                    <div class="circle"></div>
                  </div>
            </div>
          </div>
        </div>
        </div>
        </div>
        <div id="dup" class="modal dupm">
        </div>
    </main>
        <script src="manage/jquery/manage.js"></script>
    </body>
</html>

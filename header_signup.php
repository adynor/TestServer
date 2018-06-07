<?php
ob_start();
session_start();
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

$l_filehomepath= url()."/test"; 
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Zaire Projectory</title>
       <link rel="icon"  href="<?php echo  $l_filehomepath; ?>/assets/images/favicon.ico">
        <link rel="shortcut icon" href="<?php echo  $l_filehomepath; ?>/assets/images/Projectory_B1_Blue.png>" />
        <link href="<?php echo  $l_filehomepath; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo  $l_filehomepath; ?>/assets/css/master.css" rel="stylesheet">
        <link href="<?php echo  $l_filehomepath; ?>/assets/css/master1.css" rel="stylesheet">
         <script src="<?php echo  $l_filehomepath; ?>/assets/js/jquery-2.2.0.min.js"></script>
    </head>
    <body>   
 
    <nav class="navbar  navbar-fixed-top nav-cus-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle icon-bar" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a><img style="width:69px;height: 65px;margin-right: 12px;"src="<?php echo  $l_filehomepath; ?>/assets/images/Projectory_B1_Blue.png"></a>
        
        <?php if(isset($_SESSION['g_UR_id'])){ ?>
              <a type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = 'SHome.php';" style="margin-left:10px">Go to Home</a>
              <?php } ?>
              </div>
          <div class="navbar-collapse collapse " aria-expanded="false" style="height: 1px;">
              <ul class="nav navbar-right navbar-nav ady-cus-head">
            
              <?php if($_SESSION['g_UR_id']==""){?>
               <li><button type="button" class="btn btn-default navbar-btn btn-warning" onclick="location.href = 'login.php';" style="margin-left:10px">Login</button></li>
               <?php } else{?>
               <li><button type="button" class="btn btn-default navbar-btn btn-danger" onclick="location.href = 'Signout.php';" style="margin-left:10px">Sign out</button></li>
              
              <?php }?>
            </ul>
          </div>
          
        </div>
      </nav>
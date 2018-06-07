<?php
@session_start();
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

$l_filehomepath= url().'/test'; 
//if(!isset($_SESSION['g_UR_id']) && empty($_SESSION['g_UR_id'])) {

    //echo '<script>window.location.href="'.$l_filehomepath.'/Signout.php"</script>';
//}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo  $l_filehomepath; ?>/assets/images/favicon.ico">
        <title>Zaire Projectory</title>
        <link href="<?php echo  $l_filehomepath; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo  $l_filehomepath; ?>/assets/css/master.css" rel="stylesheet">
        <link href="<?php echo  $l_filehomepath; ?>/assets/css/master1.css" rel="stylesheet">
        
        <style>
            @media (min-width: 768px){
                .navbar-header {
                     width: 200px;
                }
            }
            p{
                color:black !important;
            }
        </style>
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
<?php
if($_SESSION['g_PR_id'] == NULL || $_SESSION['g_TM_id']== NULL){
     //$db= mysql_connect('localhost','root','root');
    //mysql_select_db('projectory_web');
include('db_config.php');
    if($_SESSION['g_PR_id'] == NULL){
        $user_pr_id=mysql_query("SELECT PR_id FROM Users Where UR_id='".$_SESSION['g_UR_id']."'");
        $user_pr_result=  mysql_fetch_row($user_pr_id);
      $_SESSION['g_PR_id']=$user_pr_result[0];
    } else if($_SESSION['g_TM_id']== NULL){
        $user_tm_id=mysql_query("SELECT TM_id FROM Users Where UR_id='".$_SESSION['g_UR_id']."'");
        $user_tm_result=  mysql_fetch_row($user_tm_id);
     $_SESSION['g_TM_id']=   $user_tm_result[0];
    }

}
 
if($_SESSION['g_Org_id'] =='TNT'){

$Home='Back';
} else
{ 
 $Home='Home';
 }
  
              if($_SESSION['g_UR_Type']=='S')
              {
              
                   $l_UR_PR_Type=$_SESSION['g_UR_PR_Type'];
                   
                   
              if($_SESSION['g_PR_id']!=""){
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/SHome.php\'">'.$Home.'</button>');
                }
                else{
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/Projects.php\'">'.$Home.'</button>');
                }
              }
              else if($_SESSION['g_UR_Type']=='G')
              {
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/GHome.php\'">'.$Home.'</button>');
              }
              else if($_SESSION['g_UR_Type']=='M')
              {
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/MHome.php\'">'.$Home.'</button>');
              }
              else if($_SESSION['g_UR_Type']=='C')
              {
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/CHome.php\'">'.$Home.'</button>');
              }
              else if($_SESSION['g_UR_Type']=='A')
              {
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/AHome.php\'">'.$Home.'</button>');
              }
              else if($_SESSION['g_UR_Type']=='T')
              {
                print('<button type="button" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/THome.php\'">Home</button>');
              }
              ?>
             
              </div>
              <?php if($_SESSION['g_Org_id'] != 'TNT'){ ?>
          <div class="navbar-collapse collapse " aria-expanded="false" style="height: 1px;">
               <ul class="nav navbar-right navbar-nav ady-cus-head" >
              <?php
                print('<li class=""><button type="button" style="margin-left:10px" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/EditProfile.php\'">Edit Profile</button></li>');
               print('<li class=""><button type="button" style="margin-left:10px" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/ProfileChangePassword.php\'">Change Password</button></li>');
              if($_SESSION['g_UR_Type']=='S')
              {
                print('<li class=""><button type="button" style="margin-left:10px" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/StudentHelp.php\'">How it works</button></li>');
              }
              else if($_SESSION['g_UR_Type']=='G')
              {
                print('<li class=""><button type="button" style="margin-left:10px" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/GuideHelp.php\'">How it works</button></li>');
              }
              else if($_SESSION['g_UR_Type']=='M')
              {
                print('<li class=""><button type="button" style="margin-left:10px" class="btn btn-default navbar-btn btn-info" onclick="location.href = \''.$l_filehomepath.'/MentorHelp.php\'">How it works</button></li>');
              }
              ?>
               <li><button type="button" class="btn btn-default navbar-btn btn-warning" onclick="location.href ='<?php echo $l_filehomepath; ?>/Signout.php'" style="margin-left:10px">Sign out</button></li>
            </ul>            
          </div>
          <?php } ?>
        </div>
      </nav>
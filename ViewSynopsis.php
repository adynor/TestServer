<style> 
    h3{color:639FEC !important;}
    p{color:black !important;}</style>

<?php
include('header.php');
include ('db_config.php');
if(isset($_GET['prid'])){
    $_SESSION['g_PR_id']=$_GET['prid'];
    $l_PR_id=$_GET['prid'];
    }else{

$l_PR_id=$_SESSION['g_PR_id'];

}

$Query=mysql_query('select PR_Name,PR_Desc,PR_Objective,PR_Background,PR_Functional_Requirement,PR_Non_Functional_Requirement from Projects where PR_id='.$l_PR_id.'');

$l_PR_Result=  mysql_fetch_row($Query);
//echo "<pre>";
//print_r($l_PR_Result);
//echo "</pre>";

$Query1=mysql_query('select PR_ExtraDoc_Size,PR_ExtraDoc_Name from Project_Synopsis where PR_id='.$l_PR_id.'');
$l_PR_Result1=  mysql_fetch_row($Query1);
?>
<br> <br><br>
<div class="container">
    <?php if($_SESSION['g_UR_Type'] == 'M'):?>
    <div class="col-md-4 col-md-offset-6" ><a class="btn btn-block btn-primary" href="iframetest.php">View PDF </a></div>
    <?php endif;?>
    <div class="col-md-8 col-md-offset-2" style="border: 1px solid grey;
box-shadow: 0px 0px 23px 3px grey;">
    <div class="row">
        <h3 class="text-center" style="color:#639FEC"><?php  echo htmlspecialchars_decode($l_PR_Result[0]);?></h3>
    </div>
    <div class="row">
        <h3>Description:</h3>
       <div> <?php  echo htmlspecialchars_decode($l_PR_Result[1]);?></div>
    </div>
      <div class="row">
        <h3>Objective:</h3>
        <div><?php  echo htmlspecialchars_decode($l_PR_Result[2]);?></div>
    </div>
  <?php if(isset($l_PR_Result1[0])){?>
   <div class="row"> 
        <div><a class="btn btn-primary btn-block"> Download <?php echo $l_PR_Result1[1] ?></a></div>
    </div>
    <?php } ?>
      <div class="row">
        <h3>Background:</h3>
        <div><?php  echo htmlspecialchars_decode($l_PR_Result[3]);?></div>
    </div>
      <div class="row">
        <h3>Functional Requirement:</h3>
        <div><?php  echo htmlspecialchars_decode($l_PR_Result[4]);?></div>
    </div>
      <div class="row">
        <h3>Non-functional Requirement:</h3>
        <div><?php  echo htmlspecialchars_decode($l_PR_Result[5]);?></div>
    </div>
      </div>
</div>

<?php include('footer.php');?>

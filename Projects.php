<?php

include('header_signup.php');

?>

<style>
.ribbon {
 border-radius: 42%;
 font-size: 10px !important;
 /* This ribbon is based on a 16px font side and a 24px vertical rhythm. I've used em's to position each element for scalability. If you want to use a different font size you may have to play with the position of the ribbon elements */

 width: 50%;
    
 position: relative;
 background: #E91E63;
 color: #fff;
 text-align: center;
 padding: 1em 2em; /* Adjust to suit */
 margin: 2em auto 3em; /* Based on 24px vertical rhythm. 48px bottom margin - normally 24 but the ribbon 'graphics' take up 24px themselves so we double it. */
}


li:hover {font-size: 15px; padding-left: 23px; }

.panel 
{
    border: 1px solid #f4511e;
    border-radius: 0 !important;
    transition: box-shadow 0.5s;
}
.panel-heading {
   color: #fff !important;
    /*background-color: #728C00 !important;  */
    padding: 25px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
}
.panel-footer .btn {
    margin: 15px 0;
    /*background-color: #728C00;*/
    color: #fff;
}
.panel-footer .btn:hover {
    border: 1px solid #728C00;
    background-color: #fff !important;
    color: #f4511e;
}
.glyphicon.glyphicon-one-fine-dot {
    margin-bottom: -.8em;
    overflow: hidden;
}
.glyphicon.glyphicon-one-fine-dot:before {
    content:"\25cf";
    font-size: 3em;
}
</style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
 <?php  
    include ('db_config.php');
    ?>
<div class="container" >

    <?php
    $l_TM_id=$_SESSION['g_TM_id'];
    $l_UR_Type = $_SESSION['g_UR_Type'];
    $l_UR_id = $_SESSION['g_UR_id'];
    $l_PR_id_current=$_SESSION['g_PR_id'];
    
    $timezone = new DateTimeZone("Asia/Kolkata" );
    $date = new DateTime();
    $date->setTimezone($timezone );
   $l_currentDate = $date->format( 'Ymd' );
    
    $l_UR_Details_query = 'select UR_RegistrationStatus, UR_FirstName from Users where UR_id = "'.$l_UR_id.'" AND Org_id ="'.$_SESSION['g_Org_id'].'" ';
    $l_UR_Details_result = mysql_query($l_UR_Details_query);
    $l_UR_Name_row = mysql_fetch_row($l_UR_Details_result);
    $l_UR_RegistrationStatus = $l_UR_Name_row[0];
    $l_UR_FName = $l_UR_Name_row[1];
    $_SESSION['UR_FirstName']=$l_UR_FName;
  
    if(isset($_SESSION['g_UR_id']))
    {
    print('<h2 style="margin-top: 50px; align:center;"><b> Welcome to Projectory: '.$l_UR_FName.'</b></h2>'); 
 if($l_UR_Type==='S')
        {
          $msg=1;
          $msg_view=1;
        }
        else if($_SESSION['g_UR_Type']=='G'){
     // print('<input type="button" clavalue="Enter the Portal" onClick="javascript:window.location.href=\''.$l_filehomepath.'/GHome.php\'"></input>');
     	print('<a class="btn btn-primary" href="GHome.php">Enter the Portal</a>');
     	$msg=0;
 	}

        else if($_SESSION['g_UR_Type']=='M')
        {
            //print('<input type="button" value="Enter the Portal" onClick="javascript:window.location.href=\''.$l_filehomepath.'/MHome/\'"></input>');
        print('<a class="btn btn-primary" href="MHome.php">Enter the Portal</a>');
        $msg=0;
        }
        else if($_SESSION['g_UR_Type']=='C')
        {
         $msg=0;
            //print('<input type="button" value="Enter the Portal" onClick="javascript:window.location.href=\''.$l_filehomepath.'/CHome/\'"></input>');
        print('<a class="btn btn-primary" href="CHome.php">Enter the Portal</a>');
	}
        else if($_SESSION['g_UR_Type']=='T')
        {
        // print('<input type="button" value="Enter the Portal" onClick="javascript:window.location.href=\''.$l_filehomepath.'/THome/\'"></input>');
          print('<a class="btn btn-primary" href="THome.php">Enter the Portal</a>');
          $msg=0;
	}
        else if($_SESSION['g_UR_Type']=='A')
        {
         $msg=0;
         //print('<input type="button" value="Enter the Portal" onClick="javascript:window.location.href=\''.$l_filehomepath.'/AHome/\'"></input>');
          print('<a class="btn btn-primary" href="AHome.php">Enter the Portal</a>');
 	}
 	else if($_SESSION['g_UR_Type']=='PA')
        {
         $msg=0;
         //print('<input type="button" value="Enter the Portal" onClick="javascript:window.location.href=\''.$l_filehomepath.'/AHome/\'"></input>');
          print('<a class="btn btn-primary" href="PAHome.php">Enter the Portal</a>');
 	}
    }
    else
    {  print('<br><h2><b> Welcome to Projectory</b></h2>'); 
 	print('<p><font color="003366">Already subscribed? click here to  <a href = "'.$l_filehomepath.'/login.php">Login</a> your projects</font></p><br/><br/>');
    $msg=1;
    $msg_view=0;
    }
  ?>
      </div>
<?php if($msg ===1){?> 
<!-- Container (Pricing Section) -->
<div id="pricing" class="container-fluid">
  <div class="text-center row">
    <h3 style="color:#00887A">Pricing</h3>
    <h4 style="color:#00887A">Choose a Payment Plan</h4>
    <h4 style="color:#00887A">The Price is Based on Per Project Per Student</h4>
  </div>
  <div class="row" \><h4 style="float: right;
">Need Help Deciding? <a style="cursor: pointer; margin-bottom:10px;" class="" data-toggle="modal" data-target="#myModal"> Contact Us</a></h4></div>
  <div class="row slideanim">
    <?php
    function getSolution($model='NULL',$l_currentDate='NULL'){
     $solsql='SELECT SO.SO_Name,SO.SO_id FROM Projects as PR ,Project_Solution AS PS, Solution AS SO WHERE PR.MO_id='.$model.' AND PR.PR_id=PS.PR_id AND PS.SO_id=SO.SO_id AND PR.PR_Status ="C" AND PR.PR_ReleaseDate <='.$l_currentDate.' AND PR.PR_ExpiryDate >='.$l_currentDate.' GROUP BY SO.SO_id LIMIT 0,2';
    $solarr=array();
    $squery=mysql_query($solsql);
    while($solrow=mysql_fetch_row($squery)){
    array_push ($solarr,$solrow);
    }
    
  // print_r($solarr);
   return $solarr;
    }
    $l_MO_Details_query ='select MO_id, MO_Name,MO_Amount from Model where Org_id ="ALL"';
    $l_MO_Details_result = mysql_query($l_MO_Details_query);
    while($l_MO_row =mysql_fetch_row($l_MO_Details_result))
            {
            
               $l_MO_id = $l_MO_row[0];
                $l_MO_Name = $l_MO_row[1];
                $l_MO_Amount = $l_MO_row[2];
                
                if($l_MO_Amount==0)
                {
                
                    $l_MO_Amount="Free";
                }
                if($l_MO_id==1){ 
                $BG_color= "rgb(110, 110, 110);"; $domian1='Web Technologies'; $domian2='Web Application';
                 $mhrs= " 0";
                 $mavlt=" No";
                 
                 }
                else
                if($l_MO_id==2){
                 $BG_color= "rgb(36, 84, 111);"; $domian1='Cloud Computing'; $domian2='Mobile Computing ';
                 $mhrs= " 4";
                  $mavlt=" Yes";
                 }
                else
                if($l_MO_id==3){
                $BG_color= "rgb(27, 114, 102);"; $domian1='Mobile Applications'; $domian2='Cloud Computing';
                $mhrs= " 8";
                $mavlt=" Yes";
                }
                else
                if($l_MO_id==4){
                
                $BG_color= " rgba(67, 31, 82, 0.97);"; $domian1='Mobile Computing'; $domian2='Big Data Analytics';
                $mhrs= " 12";
                $mavlt=" Yes";
                }    
 ?>
     <div class="col-sm-3 col-xs-12" >
   <?php if($l_MO_id==3){ ?>
   <span class="ribbon">
   <span class="ribbon-content">Recommended</span>
   </span>
   <?php } ?>
      <div class="panel panel-default text-center" style="border:1px solid'<?=$BG_color?>; background-color:<?=$BG_color?>">    
       <div class="panel-heading" style="<?php if($l_MO_id==3){echo 'margin-top: -13px;';} ?> background-color:<?=$BG_color?>"> 
     
   <h3><?php echo $l_MO_Name ;?> </h3>
        </div>
   <div class="panel-footer" style="height:381">
          <h3><i class="fa fa-inr"></i> <?php echo $l_MO_Amount; ?></h3>
          <?php if($msg_view ===1){?>
          <a class="btn btn-lg" style="background-color:<?php echo $BG_color ?>" href="ProjectModel01.php?g_MO_id=<?php echo $l_MO_id.'|'.$l_MO_Amount ;?>" >View </a>
          <?php }else{ ?>
          <a class="btn btn-lg" style="background-color:<?php echo $BG_color ?>" href="login.php?msg=true" >Try It </a>
          
          <?php } ?>
           <div class="panel-footer">
        Mentor Availability: <?php echo $mavlt;?>
        </div>
        <div class="panel-footer">
        Mentor Hours:<?php echo $mhrs;?>
        </div>
  
 <ul class="list-group" id="my-list">
 <?php if(count(getSolution($l_MO_id,$l_currentDate)) > 0){ ?>
 <li class="list-group-item " ><span ></span><strong>Available Domains</strong></li>
 <?php } ?>
   <!--<li class="list-group-item" style= "color: steelblue;"><span style="margin-right: 5px;" class="glyphicon glyphicon-record"></span><span><?php echo $domian1; ?></span></li>-->
    
  <?php
   foreach(getSolution($l_MO_id,$l_currentDate) as $solutions){
   ?>
    <li class="list-group-item" >
    	<span  style="margin-right: 5px;" class="glyphicon glyphicon-record"></span>
    	<?php if($msg_view ===1){?>
    	
    	<a href="ProjectModel01.php?g_MO_id=<?php echo $l_MO_id.'|'.$l_MO_Amount ;?>&&sol=<?php echo $solutions[1]; ?>" >
    	<?php } ?>
    	<span>
    		<?php echo $solutions[0]; ?>
    	</span>
    	<?php if($msg_view ===1){?>
    	</a>
    	<?php } ?>
    </li>
  
   
   <?php 
   }
  // echo getSolution();
  ?>
    
   
   
 </ul>
        </div>
      </div>      
    </div>     
            <?php } ?>
  </div>
  <div class="row" \><h4 style="float: right;
">Need Help Deciding? <a style="cursor: pointer;" class="" data-toggle="modal" data-target="#myModal"> Contact Us</a></h4></div>
    <div class="col-md-12" >
        <div class="col-md-4 col-md-offset-4"><a style=" margin-bottom: 70px; background-color:#022140; color:white;
" class="btn  btn-lg btn-block" href="<?php echo $l_filehomepath; ?>/ProjectModel01.php?g_MO_id=0|ALL"> SHOW ALL PROJECTS</a></div>
    </div>
</div>
<?php 
if(!empty($_SESSION['g_PR_id'])){
?>
<div class="current_project"     style="background-color:3AAFA9; margin-top: -70px !important;"><a> Current Projects</a></div>
<div class="current_project_details" style="margin-top: -70px !important;"></div>
<?php }?>
<?php } ?>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Feel Free to reach out to us</h5>
        </div>
        <div class="modal-body">
         <div id="result" class="alert alert-success"></div>
         
<form class="form-horizontal" id="contactForm1" role="form" method="POST" action="user_contactussend.php">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="user_name" placeholder="First & Last Name" value="" required >
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="email" name="user_email" placeholder="example@domain.com" value="" required>
		</div>
	</div>
	<div class="form-group">
		<label for="mobile" class="col-sm-2 control-label">Mobile</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" minlength="10" id="mobile" name="user_mobile" placeholder="809339989" value="" required>
		</div>
	</div>
	<div class="form-group">
		<label for="message" class="col-sm-2 control-label">Message</label>
		<div class="col-sm-10">
			<textarea class="form-control" rows="4" name="user_message"  required></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<! Will be used to display an alert to the user>
		</div>
	</div>
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$("#result").hide();

    var frm = $('#contactForm1');
    frm.submit(function (ev) {
     
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
              $('#result').html(data);
              $("#result").fadeTo(2000, 500).slideUp(500, function(){
    $("#result").slideUp(500);
    $('#contactForm1')[0].reset();
});

setTimeout(function(){
  $('#myModal').modal('hide')
}, 3000);
            }
        });

        ev.preventDefault();
        
    

    });
</script>

      <?php include('footer.php');?>
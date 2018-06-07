<?php

    //////////////////////////////////////////////
    // Name            : AHome
    // Project         : Projectory
    // Purpose         : College admin will insert the semester
    // Called By       : login01
    // Calls           :  Aview_SPayments01, aview_newstudent, aview_newGuide, AStudentResults
    // Mod history:
    //////////////////////////////////////////////
    
include ('db_config.php');
include ('header.php');  
?>
<div class="row" style="padding:10px"></div>
<div class="row" style="padding:10px"></div>
<div class="container" >
    <?php
    //session id to local variables
    $l_UR_id                     = $_SESSION['g_UR_id'];  // For the Communications table we need the from id
    $l_IT_id                      = $_SESSION['g_IT_id'];
    $l_UR_Type        = $_SESSION['g_UR_Type'];
    
    //check if the user is logged in and is a college admin
    if(is_null($l_UR_id) || $l_UR_Type!='A')
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as the college admin. Please login correctly")
        window.location.href="Signout.php"; </script> ';
        
        print($l_alert_statement );
    }
    
 
    // get the last login date and time
    $l_LastLoginDate_query = 'select  UR_LastLogin from Users where UR_id = "'.$l_UR_id.'" and Org_id="'.$_SESSION['g_Org_id'].'"';
    $l_LastLoginDate = mysql_query($l_LastLoginDate_query) or die(mysql_error());
    $l_Date=mysql_fetch_row($l_LastLoginDate);
    $l_LoginDate_res=$l_Date[0];
    
    $l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));
    
    //display the last login date and time
    print('<div class="alert alert-info"><h5 style="text-align:right">logged in at ' .$l_LoginDate_res. '</h5></div>');

    print('<table class="ady-row" >');
    //options to navigate to other pages
    
    //print('<tr><td><a href="'./Aview_SPayments02.'">Check Payments for Webinar </a></td></tr>');
    print('<tr><td><a class="btn btn-primary ady-btn"  href="AView_NewStudent.php">Check New Students</a></td></tr>');
    print('<tr><td><a class="btn btn-primary ady-btn"  href="AView_NewGuide.php">Check New Guides</a></td></tr>');
    print('<tr><td><a class="btn btn-primary ady-btn"  href="AStudent_Result.php">Student Results</a></td></tr>');
     print('<tr><td><a class="btn btn-primary ady-btn"  href="ATeamsProject_Duration.php">Teams Projects Duration</a></td></tr>');
    print('</table>');
    
    ?>

</div> 

<?php include('footer.php')?>
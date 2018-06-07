<?php
include ('db_config.php');
include ('header.php');
?>
<nav class="navbar nav-cus-top"></nav>
<div class="container" >      
    <div class="row " style="padding:0px 0px">
        
            <?php
            $l_UR_id        = $_SESSION['g_UR_id'];  // For the Communications table we need the from id
            $l_UR_Type     = $_SESSION['g_UR_Type']; 
            if(is_null($l_UR_id) || $l_UR_Type!='T')
            {
                    $l_alert_statement =  ' <script type="text/javascript">
                    window.alert("You are not authorised person. Please login correctly")
                    window.location.href="'.$l_filehomepath.'/login"; </script> ';
                    print($l_alert_statement );
            }
            ?>
            <div class="panel panel-primary" style="max-width: 400px;">
                <div class="panel-heading">
                    Zaire Statistics
                </div>
                <div class="panel-body" >
                    <?php
                   $user_query=  mysql_query("SELECT UR_id FROM  Users WHERE UR_Type ='S' OR `UR_Type` ='G' OR `UR_Type` ='M'");
                   $users=  mysql_num_rows($user_query);
                   
                   $students_query=  mysql_query("SELECT UR_id,UR_FirstName,UR_MiddleName,UR_LastName,UR_RegistrationStatus,UR_Emailid,UR_EmailidDomain,IT_id FROM  Users WHERE UR_Type ='S'");
                   $students=  mysql_num_rows($students_query);
                   
                   $guide_query=  mysql_query("SELECT UR_id FROM  Users WHERE `UR_Type` ='G'" );
                   $guides=  mysql_num_rows($guide_query);
                   
                   $mentor_query=  mysql_query("SELECT UR_id FROM  Users WHERE `UR_Type` ='M'");
                   $mentors=  mysql_num_rows($mentor_query);
                           
                   $company_query=  mysql_query("SELECT UR_id FROM  Users WHERE UR_Type ='C'");
                   $companies=  mysql_num_rows($company_query);
                   
                   $project_query=  mysql_query("SELECT `PR_id` FROM `Projects` ");
                   $projects=  mysql_num_rows($project_query);
                   $performing_students_query=mysql_query("SELECT UR_id FROM  Users WHERE PR_id IS NOT NULL AND UR_Type ='S'");
                   $performing_students=mysql_num_rows($performing_students_query);
                   $performed_students_query=mysql_query("SELECT DISTINCT(`UR_Student`) FROM Student_Results");
                   $performed_students=mysql_num_rows($performed_students_query);
                    ?>
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-info">
                            <h4> 
                                Total Users  <span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $users;?></span>
                            </h4>
                        </li>
                        <li class="list-group-item list-group-item-info">
                            <h4> 
                                 <a href="http://zaireprojects.com/test/TStaticsDetails.php?req=1" >No of Students</a><span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $students?></span>
                            </h4>
                        </li>
                        <li class="list-group-item list-group-item-info">
                            <h4> 
                                No of Projects  <span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $projects;?></span>
                            </h4>
                        </li>
                         <li class="list-group-item list-group-item-info">
                            <h4> 
                                <a href="http://zaireprojects.com/test/TStaticsDetails.php?req=2" > Confirmed Students</a><span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $performing_students;?></span>
                            </h4>
                        </li>
                         <li class="list-group-item list-group-item-info">
                            <h4> 
                                 No of Performed Students  <span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $performed_students;?></span>
                            </h4>
                        </li>
                         <li class="list-group-item list-group-item-info">
                            <h4> 
                               <a href="http://zaireprojects.com/test/TStaticsDetails.php?req=3" >No of Mentors </a><span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $mentors;?></span>
                            </h4>
                        </li>
                         <li class="list-group-item list-group-item-info">
                            <h4> 
                        <a href="http://zaireprojects.com/test/TStaticsDetails.php?req=4" > No of Guides</a><span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $guides;?></span>
                            </h4>
                        </li>
                         <li class="list-group-item list-group-item-info">
                            <h4> 
                                 No of Companies  <span class="glyphicon glyphicon-hand-right"></span>
                               <span class="label label-success label-pill pull-xs-right" style="float:right"><?php echo $companies;?></span>
                            </h4>
                        </li>
                        
                    </ul>
                </div>                    
           
        </div>
        
    </div>
</div>
<?php include('footer.php')?>

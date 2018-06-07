<?php

 include ('header.php');
 include ('db_config.php'); 
 ?>
<div class="row" style="padding:20px"></div>
<div class="row" style="padding:20px"></div>
<div class="container" >
<?php


$l_UR_Name             = $_SESSION['g_UR_Name'];

$l_UR_id = $_SESSION['g_UR_id'];
$l_UR_Type = $_SESSION['g_UR_Type'];

if(is_null($l_UR_id) || $l_UR_Type!='M')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as a Mentor. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement );
}


if(isset($_GET['PR_id']))
{
    $l_PR_id = $_GET['PR_id'];

// nav adding new table project details from here.....
$l_PR_sql = 'select PR_Name,PR_Short_Desc, PR_SynopsisURL from Projects where PR_id='.$l_PR_id.' and Org_id = "'.$_SESSION['g_Org_id'].'"';
        
        $l_PR_res = mysql_query($l_PR_sql);
       
print('<table class="ady-table-content" border=1 style="width:100%" ><th colspan=2">Project Details </th>');
 if($l_PR_row = mysql_fetch_row($l_PR_res))
        {
            $l_PR_Name      = $l_PR_row[0];
            $l_PR_Desc = htmlspecialchars_decode($l_PR_row[1]);
            $_SESSION['g_pdf_view'] = $l_PR_row[2];

            print( '<tr><td>Project Name :</td><td colspan=2> '.$l_PR_Name.' </td></tr>');
            print( '<tr><td>Project Description:</td><td colspan=2>'.$l_PR_Desc.'</td></tr>');
             ?>
             <tr><td>Project Synopsis:</td><td colspan=2><a href='<?php echo $l_filehomepath; ?>/ViewSynopsis.php?prid=<?php echo $l_PR_id; ?>'class="btn btn-primary" >View</a><a href='<?php echo $l_filehomepath; ?>/blob_download.php?prid=<?php echo $l_PR_id; ?>'class="btn btn-primary" >Download</a></td></tr>
             <?php
        }
        $l_SD_sql = 'select SD.SD_id, SD.SD_Name from SubDomain as SD, Project_SubDomains as PS where PS.SD_id = SD.SD_id and PS.Org_id = "'.$_SESSION['g_Org_id'].'" and PS.PR_id = '.$l_PR_id;
        $l_SD_res = mysql_query($l_SD_sql);
        print ('<tr><td>Technology Used </td>');
        print('<td colspan=2>');
        while($l_SD_row = mysql_fetch_row($l_SD_res))
        {
            
            $l_SD_id      = $l_SD_row[0];
            $l_SD_Name    = $l_SD_row[1];

               print($l_SD_Name.'<br/>');           
        }
        print('</td></tr>');
print('</table></form>');   // nav  new table ending till here.....


//       $l_PR_sql = 'select PR_id, PR_Name from Projects where PR_id='.$l_PR_id.' ';
       $l_PR_sql = 'select distinct(UR.UR_FirstName), UR.UR_id, TM.PR_id, PR.PR_id, PR.PR_Name
                                      from Users as UR, Projects as PR, Teams as TM
                                      where PR.PR_id='.$l_PR_id.' and TM.TM_id=UR.TM_id and TM.PR_id=PR.PR_id and TM.Org_id = "'.$_SESSION['g_Org_id'].'"' ;

       $l_PR_res = mysql_query($l_PR_sql);
        $l_PR_count = mysql_num_rows($l_PR_res);

        if($l_PR_count > 0)
        {
          $l_PR_row = mysql_fetch_row( $l_PR_res);
            $l_PR_PrjName      = $l_PR_row[4];
?>
    <br>
     <div class="panel panel-primary">
        <div class="panel-heading"><h4>Student performing this projects</h4></div>
        <div class="panel-body table-responsive table">
    <?
         print('<br><br><table class="ady-table-content" border=1 style="width:100%"  id="StudentsList"> ');
       
         print ('<tr style="align:centre; color:#4682b4;" >');
      
        print ('<th>  Student Name</th>');
        print ('<th> Student Email</th>');
//        print ('<th> Student USN</th>');
        print ('<th> Student Phone</th>');
//        print ('<th> Institute</th>');
        print ('<th> Program</th>');
//        print ('<th> Project</th>');
//        print ('<th> StudentMarks</th>');
//        print ('<th> Feedback</th>');
        print ('</tr>');

                



                $l_PR_sql = 'select distinct(UR.UR_FirstName), UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain, UR.UR_Phno,UR.UR_id, TM.PR_id, PR.PR_id, PR.PR_Name ,SR.ST_Marks, SR.ST_Feedback, SR.TM_id,UR.PG_id
                                      from Users as UR, Projects as PR, Teams as TM,Student_Results as SR
                                      where PR.PR_id='.$l_PR_id.' and TM.TM_id=UR.TM_id and  SR.PR_id = PR.PR_id
                                   ' ;
                $l_UR_res = mysql_query($l_PR_sql);
                $count= mysql_num_rows($l_UR_res)+1;
               
                        

while($l_UR_row = mysql_fetch_row($l_UR_res))
                            {$l_UR_Name      = $l_UR_row[0] . ' ' . $l_UR_row[1]. ' ' . $l_UR_row[2] ;
                             $l_UR_Emailid    =$l_UR_row[3] . '@' .$l_UR_row[4];
                             $l_UR_Phone      = $l_UR_row[5];
                             $l_UR_id      = $l_UR_row[6];
                             

$l_PG_Name='select PG_Name from Programs where PG_id='.$l_UR_row[13].''; //query for programme name
$l_PG_res = mysql_fetch_row(mysql_query($l_PG_Name));     

print('<tr style="background:#c7d9e8; color:rgb(18, 26, 18); text-align:center">');
                            print( '<td>  <a style="color: black;TEXT-DECORATION: NONE" href=./CStudentDetails03.php?UR_id='.$l_UR_id.'>' . $l_UR_Name. '</a></td> ');
                            print( '<td>' . $l_UR_Emailid. '</td>  ');
                            print( '<td>' . $l_UR_Phone. '</td>  ');
                            print( '<td>' . $l_PG_res[0]. '</td>  ');
                            print( '</tr> ');
                     
}
print('</table>');
     }// if($l_UR_count > 0)

    else
    {
        print ('<table class="ady-table-content" border=1 style="width:100%" >');
        print ('<tr>');
        print ('<th>  Project Name</th>');
        print ('<th>  Student Name</th>');
        print ('<th> Student Email</th>');
//        print ('<th> Student USN</th>');
        print ('<th> Student Phone</th>');
//        print ('<th> Institute</th>');
        print ('<th> Program</th>');
//        print ('<th> Project</th>');
//        print ('<th> StudentMarks</th>');
//        print ('<th> Feedback</th>');
        print ('</tr>');
        print ('<tr>');
        print ('<td colspan=5> No Students have done this Projects.</td>');
        print ('</tr>');
        print('</table>');
    }

    //}  ////while($l_UR_row = mysql_fetch_row($l_UR_res))
}///if(isset($_GET['PR_id']))
    //
?>
            </div>
     </div>
</div>
        <?php include('footer.php');?>
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


$l_PR_Name = 'Dummyname';
$l_SD_Name = 'Dummyname';

print('<form action="" method="POST">');
if(isset($_POST['SaveRec']))
    {
    $l_PR_Name             = $_POST['l_PR_Name'];
       $l_SD_Name             = $_POST['l_SD_Name'];
       if($l_SD_Name==-99)
           $l_SD_Name='Dummyname';
       if($l_PR_Name=='')
           $l_PR_Name='Dummyname';

    }

if ($l_PR_Name == 'Dummyname' && $l_SD_Name=='Dummyname')/////// filter
    {
         $l_query='Select distinct PR.PR_id, PR.PR_Name, PR.PR_Short_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, UR.UR_FirstName, UR.UR_LastName from Users as UR, Projects as PR where PR.UR_Owner = UR.UR_id and PR.UR_Owner = "'.$l_UR_id.'" and PR.Org_id = "'.$_SESSION['g_Org_id'].'" order by PR_Name';

    }       // if ($l_PR_Name != null)

else if ($l_PR_Name == 'Dummyname' && $l_SD_Name!='Dummyname')
    {
          $l_query='Select distinct PR.PR_id, PR.PR_Name, PR.PR_Short_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, UR.UR_FirstName, UR.UR_LastName from Users as UR,  Projects as PR, SubDomain as SD,Project_SubDomains as PS
                            where PR.PR_id=PS.PR_id
                            and PS.SD_id=SD.SD_id
                            and SD.SD_id  ="'.$l_SD_Name.'"
                            and PR.UR_Owner = UR.UR_id  
                            and PR.UR_Owner = "'.$l_UR_id.'" 
                            and PR.Org_id = "'.$_SESSION['g_Org_id'].'" ';


    }       // if ($l_PR_Name != null)

    
else if ($l_PR_Name != 'Dummyname' and $l_SD_Name=='Dummyname')///////// n o filters
    {
        $l_query='Select PR.PR_id, PR.PR_Name, PR.PR_Short_Desc, PR.PR_ComplexityLevel, PR_SynopsisURL, PR_NoOfPastAttempts, UR.UR_FirstName, UR.UR_LastName from Users as UR,  Projects as PR where PR.PR_Name like "%'.$l_PR_Name.'%" and PR.UR_Owner = UR.UR_id and PR.UR_Owner = "'.$l_UR_id.'" and PR.Org_id = "'.$_SESSION['g_Org_id'].'"';

// get the current applications and past applications
    // current applications will be known from
    }      // if ($l_PR_Name != null)

 else if ($l_PR_Name != 'Dummyname' && $l_SD_Name!='Dummyname')
    {
       /* $l_query='Select distinct PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, UR.UR_FirstName, UR.UR_LastName from Users as UR, Projects as PR,Project_SubDomains as PS,SubDomain as SD
                            where PR.PR_id=PS.PR_id
                            and PS.SD_id=SD.SD_id
                            and PR.PR_Name like "%'.$l_PR_Name.'%"
                             and SD.SD_id  ="'.$l_SD_Name.'"
                            and PR.UR_Owner="'.$l_UR_id.'" and PR.Org_id = "'.$_SESSION['g_Org_id'].'"';*/

$l_query= 'Select  PR.PR_id, PR.PR_Name, PR.PR_Short_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, UR.UR_FirstName, UR.UR_LastName from Users as UR, Projects as PR, SubDomain as SD,Project_SubDomains as PS where  PR.PR_Name like "%'.$l_PR_Name.'%" and PR.PR_id=PS.PR_id and PS.SD_id=SD.SD_id and SD.SD_id ="'.$l_SD_Name.'" and PR.UR_Owner = UR.UR_id and PR.UR_Owner = "'.$l_UR_id.'" and PR.Org_id = "'.$_SESSION['g_Org_id'].'"';


    }       // if ($l_PR_Name != null)



$l_result=mysql_query($l_query) or die(mysql_error());    // run the actual SQL

print('<div class="alert alert-info"><table class="ady-table-content"  style="width:100%" >');

print ('<tr><td>Search by Project Name  <input class="form-control" type=text name=l_PR_Name >
          </td>');

print ('<td> Filter by Technology <select class="form-control" name="l_SD_Name" align="right">');

$l_sql_SD      ='SELECT SD_id, SD_Name  FROM SubDomain';
$l_result_SD =mysql_query($l_sql_SD);
$l_row = 'Dummyname';
while ($l_data=mysql_fetch_row($l_result_SD))
{
    if($l_row == 'Dummyname')
    {
        print ('<option value="-99">--</option> ' );
    }
    else
    {
        print ('<option value='. $l_data[0]. '>'. $l_data[1]. '</option> ' );
    }
    $l_row = $l_data[0];
}
mysql_free_result($l_result_SD);
print('</select>');
print('</td>       ');

print ('<td style ="text-align:center" colspan=4>
            <input  class="btn btn-primary"type=submit name="SaveRec"  accesskey=Alt-S value="Search Project" >
           </td>');
print('</tr>');
print('</table></div>');


/////////////////////////////////////////////////////////

        $l_PR_count = mysql_num_rows($l_result);

        if($l_PR_count == 0)
        {
            print('<table class="ady-table-content" border=1 style="width:100%" >');
            print ('<tr>');
            print ('<th >Project  Name </th>');
            print ('<th > Project Description </th>');
            print ('<th>View Synopsis</th>');
            print ('<th>Mentor</th>');
            print ('<th >Edit Project </th>');            
            print ('</tr>');
            print ('<td colspan =5 style="text-align:center" >There are no projects to  show </td>');
            print('</table>');

        }
else
    {

        // print all the column header - ie the column names
        print('<table class="ady-table-content" border=1 style="width:100%" >');
        print ('<tr>');
        print ('<th >Project  Name </th>');
        print ('<th > Project Description </th>');
        print ('<th > Current  Applications</th>');
        print ('<th >View Synopsis</th>');
        print ('<th >Mentor</th>');
        print ('<th >Edit Project</th>');
        print ('</tr>');
         // Get DateTime
        $timezone = new DateTimeZone("Asia/Kolkata" );
        $date = new DateTime();
        $date->setTimezone($timezone );
        $l_CM_DateTime = $date->format( 'YmdHi' );

        
        while ($l_row = mysql_fetch_row($l_result))
             {
                   $l_PR_id                = $l_row[0];
                   $l_PR_Name              = $l_row[1];
                   $l_PR_Desc              = htmlspecialchars_decode($l_row[2]);
                   $l_PR_ComplexityLevel   = $l_row[3];
                   $l_PR_URL               = $l_row[4];
                   $l_PR_NoOfPastAttempts  = $l_row[5];
                   $l_UR_Owner             = $l_row[6].' '.$l_row[7];
                   print ('<tr >');
                   print( '<td>  <a style="color: black;TEXT-DECORATION: NONE" href=./MProjList02.php?PR_id='.$l_PR_id.'>'.$l_PR_Name.'</a></td>');
                   print( '<td>  '.$l_PR_Desc.'</td>');

                   $l_PR_NoOfCurrentApp_query  = 'Select count(TM_id) from Teams
                                                   where PR_id = '.$l_PR_id.' and TM_EndDate is NULL and Org_id = "'.$_SESSION['g_Org_id'].'"';
                   $l_PR_NoOfCurrentApp_res    = mysql_query($l_PR_NoOfCurrentApp_query);
                   $l_PR_NoOfCurrentApp_row    = mysql_fetch_row($l_PR_NoOfCurrentApp_res);
                   $l_PR_NoOfCurrentApp         = $l_PR_NoOfCurrentApp_row[0];

        
                        print ('<td>'.$l_PR_NoOfCurrentApp.'</td>');
        //                print ('<td>'.$l_PR_NoOfPastAttempts.'</td>');

               print('<td> <a type="button"  class="btn btn-primary" href="'.$l_filehomepath.'/blob_download.php?prid='.$l_PR_id.'"> Download</a></td>') ;
              
print ('<td>'.$l_UR_Owner.'</td>');
              
 print('<td> <input type=button class="btn btn-primary" value="Edit Project" onClick="location.href=\'EditProject.php?g_PR_id='.$l_PR_id.'\'" ></td>') ; 

 print ('</tr>');





               mysql_free_result($l_PR_NoOfCurrentApp_res);
              }//// while ($l_row = mysql_fetch_row($l_result))
        print('</table>');
    }


?>
</div>
<?php include('footer.php')?>
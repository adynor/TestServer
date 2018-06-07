<style type="text/css">
.TFtableCol{
width:100%; 
border-collapse:collapse; 
}


#TFtableCold tr:first-child {
   vertical-align: top;
background: #25383C;
}
    /*  Define the background color for all the ODD table columns  */
.TFtableCol tr:nth-child(odd){ 
background: #ffffff;
               color: #221E44;
}
/*  Define the background color for all the EVEN table columns  */
.TFtableCol tr:nth-child(even){
background: #dae5f4;
               color: #221E44;
}
</style>

<?php   

include ('db_config.php');
include ('header.php');

?>
<link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>

<br><br><br><br>
<div class="container" >
       <div class="row" style="width:100%;">
           <div class=" ady-row">




<?php
    
    
    $l_UR_Type= $_SESSION['g_UR_Type'];
    $l_UR_id = $_SESSION['g_UR_id'];
    
        
    print('<div style="clear:left">');
    
    if(is_null($l_UR_id) || $l_UR_Type!='T')
    {
        $l_alert_statement =  '<script type="text/javascript">        
        window.alert("You have not logged in as an admin. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';
        
        print($l_alert_statement );
    }
    
    
    if(isset($_GET['UR_id']))
    {
        $l_co_UR_id = $_GET['UR_id'];
        
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
            
            //echo "PR_Name.".$l_PR_Name.".".'<br>';
            //echo "SD_Name ".$l_SD_Name.'<br>';
        }
        
        if ($l_PR_Name == 'Dummyname' && $l_SD_Name=='Dummyname')/////// filter
        {
            $l_query='Select distinct PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, outerUR.UR_FirstName, outerUR.UR_LastName,PR.PR_Status from Users as outerUR, Projects as PR where PR.PR_id>30 and PR.PR_Status="C" and PR.UR_Owner = (select UR.UR_id from Users as UR where UR.UR_CompanyName = "'.$l_co_UR_id.'" and outerUR.UR_id = UR.UR_id) order by PR.PR_Name';
            
        }       // if ($l_PR_Name != null)
        
        else if ($l_PR_Name == 'Dummyname' && $l_SD_Name!='Dummyname')
        {
            $l_query='Select distinct PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, outerUR.UR_FirstName, outerUR.UR_LastName,PR.PR_Status from Users as outerUR,  Projects as PR, SubDomain SD,Project_SubDomains PS
            where PR.PR_id>30 and PR.PR_Status="C" and PR.PR_id=PS.PR_id
            and PS.SD_id=SD.SD_id
            and SD.SD_Name like "%'.$l_SD_Name.'%"
            and PR.UR_Owner = (select UR.UR_id from Users as UR where UR.UR_CompanyName = "'.$l_co_UR_id.'" and outerUR.UR_id = UR.UR_id) order by PR.PR_Name';
            
            
        }       // if ($l_PR_Name != null)
        
        
        else if ($l_PR_Name != 'Dummyname' and $l_SD_Name=='Dummyname')///////// n o filters
        {
            $l_query='Select PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, PR_SynopsisURL, PR_NoOfPastAttempts, outerUR.UR_FirstName, outerUR.UR_LastName,PR.PR_Status from Users as outerUR,  Projects as PR where PR.PR_id>30 and PR.PR_Status="C" and PR.PR_Name like "%'.$l_PR_Name.'%" and PR.UR_Owner = (select UR.UR_id from Users as UR where UR.UR_CompanyName = "'.$l_co_UR_id.'" and outerUR.UR_id = UR.UR_id) order by PR.PR_Name';
            
            // get the current applications and past applications
            // current applications will be known from
        }      // if ($l_PR_Name != null)
        
        else if ($l_PR_Name != 'Dummyname' && $l_SD_Name!='Dummyname')
        {
            $l_query='Select distinct PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_ComplexityLevel, PR.PR_SynopsisURL, PR.PR_NoOfPastAttempts, outerUR.UR_FirstName, outerUR.UR_LastName,PR.PR_Status from Users as outerUR, Projects as PR,Project_SubDomains PS,SubDomain SD
            where PR.PR_id>30 and PR.PR_Status="C" and PR.PR_id=PS.PR_id
            and PS.SD_id=SD.SD_id
            and PR.PR_Name like "%'.$l_PR_Name.'%"
            and SD.SD_Name like "%'.$l_SD_Name.'%"
            and PR.UR_Owner = (select UR.UR_id from Users as UR where UR.UR_CompanyName = "'.$l_co_UR_id.'" and outerUR.UR_id = UR.UR_id) order by PR.PR_Name';
            
            
        }       // if ($l_PR_Name != null)
        //    echo "PR_Name ".$l_PR_Name.'<br>';
        //echo "SD_Name ".$l_SD_Name.'<br>';
        
        
        $l_result=mysql_query($l_query);    // run the actual SQL
        
        print('<br><div class="alert alert-info" style=" margin-bottom: -26px;
    border: 1px solid;"><table  class="myTable ady-table-content"  style="width:100%; 
 " ');
        
        print ('<tr><td>Search by Project Name  <input class="form-control" type=text name=l_PR_Name ></td>');
        
        print ('<td> Filter by domain <select  class="form-control" name="l_SD_Name" align="right">');
        
        $l_sql_SD      ='SELECT SD_Name, SD_id FROM SubDomain ';
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
                print ('<option value='. $l_data[0]. '>'. $l_data[0]. '</option> ' );
            }
            $l_row = $l_data[0];
        }
        mysql_free_result($l_result_SD);
        print('</select>');
        print('</td>');
        
        print ('<td style ="text-align:center" colspan=4> <input   class="btn-primary ady-req-btn" type=submit name="SaveRec"  accesskey=Alt-S value="Search Project" ></td>');
        print('</tr>');
        print('</table></div>');
        
        
        /////////////////////////////////////////////////////////
        
        $l_PR_count = mysql_num_rows($l_result);
        
        if($l_PR_count == 0)
        { ?>
       <div style="margin-bottom: -7px;">
           <table   class=" ady-table-content" border=1 style="width:100%">
         <tr>
           <th >Project  Name </th>
           <th >Project Description </th>
           <th >View Synopsis</th>
           <th >Mentor</th>
            
        </tr>
            
          <td colspan =4 style=" font-weight:bold; margin-left:50%" ><br>There are no projects to  show </td>
 </table>
       </div>
               <br>
            
        <?php }
     else
        {  
            ?>
            
           
<br><br>
 <table id="myTable" class=" TFtableCol" cellspacing="0" width="100%" >

   <thead>
     <tr>
     <td>SL No.</td>
     <td>Project  Name </td>
     <td> Project Description </td>
     <td> Current <br> Applications</td>
     <td>View Synopsis</td>
     <td>Mentor</td>
     </tr>
  </thead>
<tbody>
           <?php
            $timezone = new DateTimeZone("Asia/Kolkata" );
            $date = new DateTime();
            $date->setTimezone($timezone );
            $l_CM_DateTime = $date->format( 'YmdHi' );
            
            $i=1;
            while ($l_row = mysql_fetch_row($l_result))
            {   
                $l_PR_id                = $l_row[0];
                $l_PR_Name              = $l_row[1];
                $l_PR_Desc              = htmlspecialchars_decode($l_row[2]);
                $l_PR_ComplexityLevel   = $l_row[3];
                $l_PR_URL               = $l_row[4];
                $l_PR_NoOfPastAttempts  = $l_row[5];
                $l_UR_Owner             = $l_row[6].' '.$l_row[7];
                if($l_row[8]=="C"){
              
                  $PR_Status="<br><span style='color:olivedrab;'>Confirmed</span>";  
                }  else {
                    
                   $PR_Status="<br><span style='color:tomato;'>Pending</span>";   
                }
              ?>
<tr>
<td><?php echo $i; ?></td>
<td>  <a style="color: black;TEXT-DECORATION: NONE" href="AdminProjectDetails.php/?PR_id=<?php echo $l_PR_id?>"><?php echo $l_PR_Name.'-'.$l_PR_id; ?><?php echo $PR_Status; ?></a></td>
<td><?php echo $l_PR_Desc;?> </td>
                <?php
                $l_PR_NoOfCurrentApp_query  = 'Select count(TM_id) from Teams
                where PR_id = '.$l_PR_id.' and TM_EndDate is NULL';
                $l_PR_NoOfCurrentApp_res    = mysql_query($l_PR_NoOfCurrentApp_query);
                $l_PR_NoOfCurrentApp_row    = mysql_fetch_row($l_PR_NoOfCurrentApp_res);
                $l_PR_NoOfCurrentApp         = $l_PR_NoOfCurrentApp_row[0];
                ?>
               <td><?php echo $l_PR_NoOfCurrentApp; ?></td>
               <td> <button class='btn-primary ady-req-btn' type=button onClick="location.href='<?php echo $l_PR_URL; ?>'">Download</button></td>
                <td><?php echo $l_UR_Owner; ?></td>
                </tr>  
           <?php
                $i++;
            }?>
           </tbody>
</table>

        <?php }
       
    }
    ?>
              
<script>
$(document).ready(function(){
    $('#myTable').DataTable();
});
</script>
               </div></div></div>
<?php include('footer.php')?>
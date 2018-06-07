<style>
    .panel-heading:after {
    content: '\02795';
    font-size: 13px;
    color: #777;
    float: right;
    margin-left: 5px;
}

.panel-heading.active:after {
    content: "\2796";
}

</style>
<?php
include('header.php');
include ('db_config.php');
print('<div class="row" style="padding:10px"></div><div class="container" >'); 
$current_url= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";




$timezone = new DateTimeZone("Asia/Kolkata");
$date = new DateTime();
$date->setTimezone($timezone);
$l_Datetime = $date->format('YmdHi');

 $l_sql=$_REQUEST['PR_id'];
   // echo $l_sql=str_replace("\\","",$l_sql);
    $l_arry = explode("|",$l_sql);
    $l_PR_id= $l_arry[0];

    $l_UR_Type= $_SESSION['g_UR_Type'];
    $l_UR_id = $_SESSION['g_UR_id'];
    
    
if($l_PR_id=="" ||$l_PR_id==NULL)
{

 $l_Completed_PR_sql='select PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,ST.ST_ResultDate,ST.ST_Marks,ST.ST_Feedback from Student_Results as ST,Projects  as PR  where UR_Student="'.$l_UR_id.'" and PR.PR_id=ST.PR_id';   
 $l_query=  mysql_query($l_Completed_PR_sql);
 $i=1;
 echo '<br><br><br><div class="panel-group" id="accordion">';
  while($results=mysql_fetch_row($l_query))
    {
  ?>
 
<div class="panel panel-primary">
   <a style="color:white; text-decoration: none; " data-toggle="collapse" data-parent="#accordion" href="#<?php echo $i; ?>" ><div class="panel-heading" style="background-color: #337AB7;"><strong style="font-size: 15px">Project <?php echo $results[0]; ?></strong> </div></a>
  <div id="<?php echo $i; ?>" class="panel-body panel-collapse collapse">
<table border=1 class="ady-table-content" style="width:100%">
<tr><th>Project Name:</th><td><?php echo $results[0]; ?></td></tr>    
<tr><th>Project Description:</th><td><?php echo htmlspecialchars_decode($results[1]); ?></td></tr>  
<tr><th>Project Duration:</th><td><?php echo $results[3]; ?> days</td></tr> 
<tr><th>Project Result Date:</th><td><?php echo date("d-M-Y ", strtotime($results[4])); ?></td></tr>  
<tr><th>Marks obtained:</th><td><?php echo $results[5]; ?></td></tr>
<tr><th>Feedback:</th><td><?php echo $results[6]; ?></td></tr>
</table>
     </div>
</div>
 
<br>
<script>
var acc = document.getElementsByClassName("panel-heading");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
  }
}
</script>
<?php
 $i++;
    } 
    echo"</div>"; 

}
else
{
// query to find completed projects by the user.
    
    
    

print('<br><br><br><br><br><div class="panel panel-primary">
  <div class="panel-heading">Performed Project Marks Obtained</div>
  <div class="panel-body table-responsive">');
print('<table border=1 class="ady-table-content" style="width:100%">');
        
if(is_null($l_UR_id)||$l_UR_Type !='S')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in. Please login correctly");
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement );
}

if(isset($_POST['download']))
{
     header('Location:'.$l_filehomepath.'/PDFView');
}

print ('<form action="" method="POST">');
if(isset($l_PR_id) && isset($l_UR_id))
    {
       // $l_PR_id = $_GET['PR_id'];
        
        $l_PR_sql = 'select PR_Name, PR_Desc, PR_SynopsisURL,PR_Duration,PR_ip from Projects where PR_id='.$l_PR_id.' ';
        $l_PR_res = mysql_query($l_PR_sql);
        
  
       $l_marks_sql = 'select ST.ST_Marks, ST.ST_Feedback, ST.ST_ResultDate,UR.UR_FirstName,ST.TM_id from Student_Results as ST,Users as UR where ST.UR_Student="'.$l_UR_id.'" and ST.UR_Student=UR.UR_id and ST.PR_id='.$l_PR_id.'';
        
        $l_marks_res = mysql_query($l_marks_sql);
        $l_Result_row = mysql_fetch_row($l_marks_res);
        $l_TM_id= $l_Result_row[4];
        if($l_PR_row = mysql_fetch_row($l_PR_res))
        {
            $l_PR_Name      = $l_PR_row[0];
            $l_PR_Desc = $l_PR_row[1];
            $_SESSION['g_pdf_view'] = $l_PR_row[2];
$l_PR_DurationMonth = ($l_PR_row[3]/30);
$l_PR_Duration_months=round($l_PR_DurationMonth,1);
$l_PR_Duration_Weeks = round($l_PR_row[3]/7);
            $l_PR_NDAstatus = $l_PR_row[4];
print( '<tr><td>Performer Name :</td><td colspan=2> '.$l_Result_row[3].' </td></tr>');           
print( '<tr><td>Project Name :</td><td colspan=2> '.$l_PR_Name.' </td></tr>');
print( '<tr><td>Project Description:</td><td colspan=2>'.$l_PR_Desc.'</td></tr>');
                        
print( '<tr><td>Project Duration:</td><td colspan=2>'.$l_PR_Duration_Weeks.'Weeks/'.$l_PR_Duration_months.'Months</td></tr>');

print( '<tr><td>Marks obtained:</td><td colspan=2>'.$l_Result_row[0].' / 100</td></tr>');



}
        $l_SD_sql = 'select SD.SD_id, SD.SD_Name from SubDomain as SD, Project_SubDomains as PS where PS.SD_id = SD.SD_id and PS.PR_id = '.$l_PR_id;
        $l_SD_res = mysql_query($l_SD_sql);
        print ('<tr><td>Technology Used -:</td>');
        print('<td colspan=2><ul style="margin:0px; padding:0px;list-style-type: none;">');
        while($l_SD_row = mysql_fetch_row($l_SD_res))
        {
            
            $l_SD_id      = $l_SD_row[0];
            $l_SD_Name    = $l_SD_row[1];

               print('<li style="background:menu;padding:5px ;border-bottom:1px solid #FFFFFF">'.$l_SD_Name.'</li>');           
        }
        print('</ul></td></tr>');
        

$l_TM_Member_sql='select UR.UR_FirstName from Users as UR,Student_Results as ST where ST.TM_id='.$l_TM_id.' and ST.UR_Student=UR.UR_id';      
$l_TM_res = mysql_query($l_TM_Member_sql);
print ('<tr><td>Team Members-:</td>'); 
print('<td colspan=2><ul style="margin:0px; padding:0px;list-style-type: none;">');
while($l_TM_name_row = mysql_fetch_row($l_TM_res))
        {
            
            $l_TM_Member_Name    = $l_TM_name_row[0];

               print('<li style="background:menu;padding:5px ;border-bottom:1px solid #FFFFFF">'.$l_TM_Member_Name.'</li>');           
        }
print('</ul></td></tr>');
print( '<tr><td>Feedback:</td><td colspan=2><b>'.$l_Result_row[1].'</b></td></tr>');
print( '<tr><td>Result Declared date:</td><td colspan=2><b>'.date("d-M-Y ", strtotime($l_Result_row[2])).'</b></td></tr>');
?>
<tr>
    <td>Documents:</td>
    <td colspan=2 >
        <table class="table ">
            <?php
                  $l_team_doc_query=mysql_query('SELECT  PD.PD_Name,PD.PD_URL,PD.PD_Status FROM Project_Documents as PD WHERE PD.TM_id='.$l_TM_id.'');
           while($l_doc_rows=  mysql_fetch_array($l_team_doc_query)){
                  ?>
            <tr>
                <td><?php echo $l_doc_rows['PD_Name']; ?></td>
                <td><a href="zdownload.php?file=<?php echo $l_doc_rows['PD_URL']; ?>" class="btn btn-primary">Download</a></td>
                 <td><?php if($l_doc_rows['PD_Status']=='A') 
                                echo "Accepted";
                      else
                          echo"Rejected"; 
                      ?></td>
            </tr>
            <?php } ?>
        </table>
    </td>
</tr>
<?php
print('</table><br>');
        
    }
}
?>
</div></div></div>


      <?php include('footer.php');?>
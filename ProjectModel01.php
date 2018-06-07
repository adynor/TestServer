<style>
.scrollToTop{
	width:100px; 
	height:100px;
	padding:10px; 
	text-align:center; 
	background: none;
	font-weight: bold;
	color: blue;
	text-decoration: none;
	position:fixed;
	bottom:0px;
	right:40px;
	display:none;
	
}


.scrollToTop:hover{
	text-decoration:none;
}

.morectnt span {

display: none;

}

      .hamariclass table {
            width: 100%;
        }

        .hamariclass thead, .hamariclass tbody, .hamariclass tr,.hamariclass td,.hamariclass th { display: block; }

       .hamariclass tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }

       .hamariclass thead th {
            height: 65px;

            /*text-align: left;*/
        }

        .hamariclass tbody {
            height: 410px;
            overflow-y: auto;
        }

       .hamariclass thead {
            /* fallback */
        }


       .hamariclass tbody td, thead th {
            width: 20%;
            float: left;
        }
        0em;
}


</style>

<?php
include('header.php');
include ('db_config.php');


print('<div class="row" style="padding:10px"></div><div class="container" >'); 

$l_projects=array();
$l_TM_id=$_SESSION['g_TM_id'];
$l_UR_Type = $_SESSION['g_UR_Type'];
$l_UR_id = $_SESSION['g_UR_id'];
$l_PR_id_current=$_SESSION['g_PR_id'];
$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$l_currentDate = $date->format( 'Ymd' );

    $l_sql=$_REQUEST['g_MO_id'];
    $l_sql=str_replace("\\","",$l_sql);
    $l_arry = explode("|",$l_sql);
    $l_MO_id= $l_arry[0];
    $l_MO_amount= $l_arry[1];
    $l_PR_id_ended= array();
    

  
 $l_UR_FName=$_SESSION['UR_FirstName'];
 
                            $l_query_PR_ended='select ST.PR_id from Student_Results as ST where ST.UR_student ="'.$l_UR_id.'" and ST.Org_id="'.$_SESSION['g_Org_id'].'"';
                            $_result_PR_ended=mysql_query($l_query_PR_ended) or die(mysql_error());
                            if($result_PR_ended=== FALSE)
                            {
                                die(mysql_error()); // TODO: better error handling
                            }
                            $i=0;
                            while($l_row_PR_ended = mysql_fetch_row($_result_PR_ended))
                            {
                                $l_PR_id_ended[$i]=$l_row_PR_ended[0];
                                $i++;
                            }


print('<br><br><h2><b> Welcome to Projectory : '.$l_UR_FName.'</b></h2>');


//////////////////////filter starts

 $l_PR_Name = 'Dummyname';
        $l_SD_Name = 'Dummyname';
        $l_IN_Name='Dummyname';
        $l_SO_Name='Dummyname';
        $l_MO_Name ='Dummyname';
        if(isset($_GET['sol'])){
        @$l_SO_Name             =$_GET['sol'];
        }
        print('<form action="" method="POST">');
        if(isset($_POST['SaveRec']))
        {
        $l_PR_Name             = $_POST['l_PR_Name'];
        $l_SD_Name             = $_POST['l_SD_Name'];
        $l_IN_Name             = $_POST['l_IN_Name'];
        $l_SO_Name             = $_POST['l_SO_Name'];
       $l_MO_Name          = $_POST['l_MO_Name']; 
           
            if($l_SD_Name==-99)
              $l_SD_Name='Dummyname';
            if($l_PR_Name=='')
               $l_PR_Name='Dummyname';
            if($l_IN_Name==-99)
               $l_IN_Name='Dummyname';
            if( $l_SO_Name==-99)
               $l_SO_Name='Dummyname';
               if($l_MO_Name ==-99)
               $l_MO_Name ='Dummyname';

       }
$sql_tagetorg= 'select OC.TargetOrg from Organisation_Customers  as OC WHERE OC.Org_id="'.$_SESSION['g_Org_id'].'"';
 $res_target=mysql_query($sql_tagetorg);
 while($row_taget =  mysql_fetch_row($res_target)){
     $TargetOrg=$row_taget[0];
$sql= 'select  PR.PR_id, PR.PR_Name, PR.PR_Short_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects  as PR,Model as MO';
if($l_SD_Name!='Dummyname'){
  $sql.=',SubDomain as SD ,Project_SubDomains as PS ';
}
if($l_SO_Name!='Dummyname'){
$sql.=',Project_Solution as PO ';  
}
$sql.=' where  MO.MO_id=PR.MO_id and PR.PR_Status="C" and PR.PR_ReleaseDate<='.$l_currentDate.' and PR.PR_ExpiryDate >='.$l_currentDate.'';
if($l_PR_Name!='Dummyname'){
 $sql.=' and PR.PR_Name like "%'.$l_PR_Name.'%"';
}
if($l_SD_Name!='Dummyname'){
 $sql.=' and PR.PR_id=PS.PR_id and PS.SD_id ="'.$l_SD_Name.'"';
}
if($l_SO_Name!='Dummyname'){
 $sql.=' and PR.PR_id=PO.PR_id and PO.SO_id ='.$l_SO_Name; 
}

if($l_MO_id ==0){
	if($l_MO_Name !='Dummyname'){
 		$sql.=' and PR.MO_id ='.$l_MO_Name ; 
	}
}
else
{

 $sql.=' and PR.MO_id ='.$l_MO_id;

}
if($l_IN_Name!='Dummyname')
{
 $sql.=' and PR.IN_id ='.$l_IN_Name;
}
$sql.=' and PR.Org_id="'.$TargetOrg.'"';
$sql.=' GROUP BY PR.PR_id ';
$sql.=' Order BY PR.PR_Name ';

$l_query_project=  $sql; 
$l_result_project=mysql_query($l_query_project) or die(mysql_error());
while ($l_row_project2 = mysql_fetch_row($l_result_project)){
    //print_r($l_row_project2);
    array_push($l_projects,$l_row_project2);
    
}
$l_count_project=count($l_projects);
 }
 //echo  "<pre>";
//print_r($l_projects);
//exit();
?>
 <div class="alert alert-info"><table  class="ady-table-content" style="width:100%" cellpadding=1px  cellspacing=1px >
<tr>
    
    <td >
        <span style="font-size:small;">Filter by Technology</span> 
        <select  class="form-control" name="l_SD_Name" align="right">
            <?php 
            $l_sql_SD      ='SELECT SD_Name, SD_id FROM SubDomain WHERE  Org_id="ALL"';
            $l_result_SD =mysql_query($l_sql_SD);
            $l_row = 'Dummyname';
            while ($l_data=mysql_fetch_row($l_result_SD))
                  {
                      if($l_row == 'Dummyname')
                      {
                          print ('<option value="-99">All</option> ' );
                      }
                      else
                      {
                         // print ('<option value='. $l_data[1]. '>'. $l_data[0]. '</option> ' );
                     
                     ?>
                    <option value="<?php echo $l_data[1] ?>"  <?php if($l_SD_Name == $l_data[1]){ echo "selected";}?> ><?php echo $l_data[0] ;?></option>
      <?php
                     
                      }
                      $l_row = $l_data[0];
                  }
            mysql_free_result($l_result_SD);
            ?>
        </select>
    </td>
        
        <td> 
           <span style="font-size:small;"> Filter by Industry </span>
            <select  class="form-control" name="l_IN_Name" align="right">
                <?php 
                    $l_sql_IN      ='SELECT IN_Name, IN_id FROM Industry WHERE  Org_id="ALL"';
                    $l_result_IN =mysql_query($l_sql_IN);
                     print ('<option value="-99">All</option> ' );
                    while ($l_dataIN=mysql_fetch_row($l_result_IN))
                    {
                    $l_row = $l_dataIN[0];
                    //print ('<option value='. $l_dataIN[1]. '>'. $l_dataIN[0]. '</option> ' );
                   
                    ?>
                    <option value="<?php echo $l_dataIN[1] ?>"  <?php if($l_IN_Name == $l_dataIN[1]){ echo "selected";}?> ><?php echo  $l_dataIN[0] ;?></option>
      <?php
                   
                    }
                    mysql_free_result($l_result_IN);
                ?>
            </select>
       </td>
        
       <td> 
         <span style="font-size:small;">  Filter by Solutions </span>
           <select  class="form-control" name="l_SO_Name" align="right">
            <?php
                $l_sql_SO      ='SELECT SO_Name, SO_id FROM Solution WHERE  Org_id="ALL"';
                $l_result_SO =mysql_query($l_sql_SO);
                print ('<option value="-99">All</option> ' );
                while ($l_dataSO=mysql_fetch_row($l_result_SO))
                    {
                    $l_row = $l_dataSO[0];
                   // print ('<option value='. $l_dataSO[1]. if($l_SO_Name == $l_dataSO[1]){ selected}?>'>'.$l_dataSO[0]. '</option> ' );
                    
                     ?>
                    <option value="<?php echo $l_dataSO[1] ?>"  <?php if($l_SO_Name == $l_dataSO[1]){ echo "selected";}?> ><?php echo $l_dataSO[0] ;?></option>
      <?php
                    
                    }
                    mysql_free_result($l_result_SO);
            ?>
            </select>
            
          </td><td>
          
          <?php if($l_MO_id == 0)
          {
          
           $models=array();
            $model_query=mysql_query('SELECT MO_Name,MO_id FROM Model as MO WHERE MO.Org_id="ALL"');
                   while($model_list=mysql_fetch_row($model_query)){
                       
                         array_push($models, $model_list);
                     }

          
          
          
           ?>
          
          
         <span style="font-size:small;">  Search by Model </span>
                <select class="form-control" name="l_MO_Name">
                    <option value="<?php echo '-99';?>" >All</option>
                    <?php  
                    for ($row = 0; $row < count($models); $row++) {
                    ?>
                    <option value="<?php echo $models[$row][1]; ?>"  <?php if($l_MO_Name == $models[$row][1]){ echo "selected";}?> ><?php echo $models[$row][0] ;?></option>
      <?php
}
                    ?>
                </select>
        <?php } ?>
        
        
        
        
            
        </td>
        
        <td>
      <span style="font-size:small;">  Search by Project Name </span>
        <input class="form-control" value="<?php if(isset($_POST['l_PR_Name'])) { echo htmlentities ($_POST['l_PR_Name']); }?>" type=text name=l_PR_Name >
    </td>
        <td style ="text-align:center" colspan=4>
            <input class="btn btn-primary" type=submit name="SaveRec"   accesskey=Alt-S value="Search Projects" >
           
        </td>
        </tr>
       </table>
       <a  href="advanceprojectsearch.php" >Advance Search</a>
 </div>
 <br> 
       <div class="table-responsive col-md-12 "><?php echo 'Total Projects : ' . $l_count_project;  ?>
       
       
        <table border=1 class="ady-table-content hamariclass" style="width:100%">
<?php 

$l_count_project = count($l_projects);
?>

<thead>
    <tr>
<th style="width:20%"> Project Name </th>
<th style="width:40%"> Project Description</th>
<th style="width:10%"> Project Duration </th>   
<th style="width:10%">  Project Model </th>                        
<th style="width:10%"> No. of People Performing </th>
<th style="width:10%">Action</th>
 </tr>
  </thead>
 
 <?php                           

if($l_count_project==0)
                            {
                             ?>
<tr>
    <td colspan=5>
        There are no projects under this domain
    </td>
</tr>
    <?php
                            }
                            else
                            {
                              foreach ($l_projects as $l_row_project)
                              {
                                $l_PR_id=$l_row_project[0];
                                $l_PR_count_sql = 'select PR_id from Users where PR_id='.$l_PR_id.' ';
                                $l_PR_count_res = mysql_query($l_PR_count_sql);
  
if($l_PR_count_res!=Null)
 {
 $l_PR_count= mysql_num_rows($l_PR_count_res);    
 }


                                $l_PR_Name=$l_row_project[1];
                                $l_PR_Desc=htmlspecialchars_decode($l_row_project[2]);
                                $l_PR_Duration=round(($l_row_project[4]/7));
                                
                                
                                ?>
                            <tr>
                              <td style="width:20%"><?php  echo $l_PR_Name; ?> <a  title="<u>Technologies Involved</u>" data-toggle="popover" data-trigger="hover" 
                              
                              
                              
  data-html="true" data-content="<?php 
$pquery='select SD.SD_Name from SubDomain as SD,Project_SubDomains as PS
 where SD.SD_id=PS.SD_id and PR_id='.$l_PR_id.' group by SD.SD_Name';
 $runp=mysql_query($pquery);
echo '<ul>';
 while($tech_name=mysql_fetch_row($runp)){
 echo '<li>';
 echo $tech_name[0]; 
 echo '</li>';
 
  }
  echo '</ul>';
  ?>">
  
  
  <span class="glyphicon glyphicon-info-sign" ></span></a>
  </td>
                                <td style="width:40%" ><div class="show"><?php echo $l_PR_Desc; ?> </div>
                                </td>
                               <td style="width:10%"><?php echo $l_PR_Duration; ?>  Weeks</td>
                               <td style="width:10%"><?php echo $l_row_project[5]; ?></td>
                                <td style="width:10%"> <?php echo $l_PR_count; ?> Students</td>
                                <td style="width:10%">                
    <?php 
    if($l_PR_id==$l_PR_id_current)
                                {
                                    print('<input class="btn btn-primary" type="button" value="Continue Project" onClick="javascript:window.location.href=\''.$l_filehomepath.'/SHome.php\'"></input>');
                                }
                                else if(in_array($l_PR_id, $l_PR_id_ended))
                                {
                                    print('<a class="btn btn-primary" type="button" value="" href="'.$l_filehomepath.'/completedprojects.php?PR_id='.$l_PR_id.'">View Project</a>');
                                }
                                else
                                {
                                    if($l_PR_id_current==NULL){
                                    ?>
                                        <input class="btn btn-primary" name="performproject"  id="<?php echo $l_PR_id; ?>" type="button" value="Enroll" onClick="javascript:window.location.href='<?php echo $l_filehomepath; ?>/ProjectDetails.php?PR_id=<?php echo $l_PR_id ;?>|<?php echo $l_MO_amount; ?>&&model=<?php echo $l_MO_id ?>'"></input><br/>
                                 <?php   }
                                    else
                                    {
                                        print('<h5>You are already enrolled to another project. Contact our representatives for more details.</h5>');
                                        
                                    }
                                }
                                ?>
                                </td>
                            </tr>
                                <?php 
                              }
                            }
                            ?>
                           </table>
                            
                       



</div>
<?php 
if(!empty($_SESSION['g_PR_id'])){
?>
<div class="current_project" style="margin-top: -70px;"> Current Projects</div>
<div class="current_project_details"  style="margin-top: -70px;"></div>
<?php } ?>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});



$(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
});



</script>

<script type="text/javascript">
	$(function() {

var showTotalChar = 200, showChar = "Show more", hideChar = "Show Less";

$('.show').each(function() {

var content = $(this).text();

if (content.length > showTotalChar) {

var con = content.substr(0, showTotalChar);

var hcon = content.substr(showTotalChar, content.length - showTotalChar);

var txt= con +  '<span class="dots">......</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="" class="showmoretxt">' + showChar + '</a></span>';

$(this).html(txt);

}

});

$(".showmoretxt").click(function() {

if ($(this).hasClass("sample")) {

$(this).removeClass("sample");

$(this).text(showChar);

} else {

$(this).addClass("sample");

$(this).text(hideChar);

}

$(this).parent().prev().toggle();

$(this).prev().toggle();

return false;

});

});

</script>



      <?php echo '<a href="#" class="scrollToTop"><span class="glyphicon glyphicon-arrow-up" style="color: #34383a; font-size:30px;"> </span></a>'; 
      
     
      include('footer.php');?> <br/><br/>
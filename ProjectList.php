<?php
include('header.php');
include ('db_config.php');

?>
<div class="container " >

<?php
$l_TM_id=$_SESSION['g_TM_id'];
$l_UR_Type = $_SESSION['g_UR_Type'];
$l_UR_id = $_SESSION['g_UR_id'];
$l_PR_id_current=$_SESSION['g_PR_id'];
$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$l_currentDate = $date->format( 'Ymd' );

//    $l_sql=$_REQUEST['g_MO_id'];
//    $l_sql=str_replace("\\","",$l_sql);
//    $l_arry = explode("|",$l_sql);
//    $l_MO_id= $l_arry[0];
//    $l_MO_amount= $l_arry[1];

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
            $models=array()    ;
            $model_query=mysql_query('SELECT MO_Name,MO_Amount FROM Model as MO WHERE MO.Org_id="ALL"');
                   while($model_list=mysql_fetch_row($model_query)){
                       //print_r($model_list);
                         array_push($models, $model_list);
                     }

?>
<?php
print('<h2><b> Welcome to Projectory : '.$l_UR_FName.'</b></h2>');


//////////////////////filter starts

 $l_PR_Name = 'Dummyname';
        $l_SD_Name = 'Dummyname';
        if(isset($_POST['SaveRec']))
        {
            $l_PR_Name        = $_POST['l_PR_Name'];
            $l_SD_Name             = $_POST['l_SD_Name'];
            $l_MO_Name             = $_POST['l_MO_Name'];
            if($l_SD_Name==-99)
                $l_SD_Name='Dummyname';
            if($l_PR_Name=='')
                $l_PR_Name='Dummyname';
            if($l_MO_Name == '')
                $l_MO_Name='Dummyname';
            
          
        } else{
             $l_SD_Name='Dummyname';
             $l_PR_Name='Dummyname';
             $l_MO_Name='Dummyname';
        }
        
        if ($l_PR_Name == 'Dummyname' && $l_SD_Name=='Dummyname' &&  $l_MO_Name =='Dummyname' )/////// filter
        {
          
           $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects as PR,Model as MO where MO.MO_id=PR.MO_id and PR.PR_Status="C" and  PR.PR_ReleaseDate<='.$l_currentDate.' and PR.PR_ExpiryDate >='.$l_currentDate.'';


        }       // if ($l_PR_Name != null)
        
        else if ($l_PR_Name == 'Dummyname' && $l_SD_Name!='Dummyname' && $l_MO_Name =='Dummyname')
        {
            
        $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects  as PR,SubDomain as SD,Project_SubDomains as PS,Model as MO
           where  MO.MO_id=PR.MO_id and PR.PR_Status="C" 
           and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'
            and PR.PR_id=PS.PR_id 
            and PS.SD_id=SD.SD_id
            and SD.SD_Name="'.$l_SD_Name.'"
           ';
            
        }       // if ($l_PR_Name != null)
       else if ($l_PR_Name != 'Dummyname' && $l_SD_Name=='Dummyname' &&  $l_MO_Name =='Dummyname')///////// n o filters
        {
             $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects 
            as PR,Model as MO where MO.MO_id=PR.MO_id and PR.PR_Status="C"  
            and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'
            and PR.PR_Name like "%'.$l_PR_Name.'%"';

            // get the current applications and past applications
            // current applications will be known from
        }      // if ($l_PR_Name != null)
        
        else if ($l_PR_Name != 'Dummyname' && $l_SD_Name!='Dummyname' &&  $l_MO_Name =='Dummyname')
        {
              $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects  as PR,SubDomain as SD,Project_SubDomains as PS,Model as MO 
           where  MO.MO_id=PR.MO_id and PR.PR_Status="C" 
           and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'
            and PR.PR_id=PS.PR_id 
            and PS.SD_id=SD.SD_id
            and SD.SD_Name="'.$l_SD_Name.'" and PR.PR_Name like "%'.$l_PR_Name.'%"';
            
        }     
        else if ($l_PR_Name == 'Dummyname' && $l_SD_Name =='Dummyname' &&  $l_MO_Name !='Dummyname')
        {
           $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects 
            as PR  ,Model as MO where  PR.MO_id=MO.MO_id and MO.MO_Amount="'.$l_MO_Name.'" and PR.PR_Status="C"  
            and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'';
        }
         else if ($l_PR_Name == 'Dummyname' && $l_SD_Name !='Dummyname' &&  $l_MO_Name !='Dummyname')
        {
             $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects  as PR,SubDomain as SD,Project_SubDomains as PS ,Model as MO
           where PR.MO_id=MO.MO_id 
           and MO.MO_Amount="'.$l_MO_Name.'"
            and PR.PR_Status="C" 
            and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'
            and PR.PR_id=PS.PR_id 
            and PS.SD_id=SD.SD_id
            and SD.SD_Name="'.$l_SD_Name.'"';
        }
         else if ($l_PR_Name != 'Dummyname' && $l_SD_Name =='Dummyname' &&  $l_MO_Name !='Dummyname')
        {
            $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects 
            as PR ,Model as MO where  PR.MO_id=MO.MO_id and MO.MO_Amount="'.$l_MO_Name.'" and  PR.PR_Status="C"  
            and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'
            and PR.PR_Name like "%'.$l_PR_Name.'%"'; 
            
        }  else if ($l_PR_Name != 'Dummyname' && $l_SD_Name !='Dummyname' &&  $l_MO_Name !='Dummyname')
        {
           $l_query_project='select  PR.PR_id, PR.PR_Name, PR.PR_Desc, PR.PR_SynopsisURL,PR.PR_Duration,MO.MO_Name from Projects  as PR,SubDomain as SD,Project_SubDomains as PS,Model as MO
           where   PR.MO_id=MO.MO_id and MO.MO_Amount="'.$l_MO_Name.'" and  PR.PR_Status="C" 
           and PR.PR_ReleaseDate<='.$l_currentDate.'
            and PR.PR_ExpiryDate >='.$l_currentDate.'
            and PR.PR_id=PS.PR_id 
            and PS.SD_id=SD.SD_id
            and SD.SD_Name="'.$l_SD_Name.'" and PR.PR_Name like "%'.$l_PR_Name.'%"'; 
        }
        else {
           
        }
        $l_result=mysql_query($l_query);    // run the actual SQL
        ?>
    <div class="row">
<form role="form" action="" method="POST">
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group">
                <label for="email">Search by Project Name </label>
                <input type="text" name="l_PR_Name" class="form-control" id="">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Search by Technology </label>
                <select class="form-control" name="l_SD_Name">
                     <?php
                    $l_sql_SD      ='SELECT SD_Name, SD_id FROM SubDomain WHERE  Org_id="ALL"';
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
                            ?>
                    <option value="<?php echo $l_data[0]; ?>" <?php if($l_SD_Name == $l_data[0]){ echo "selected";}?>><?php echo $l_data[0];?></option> 
                        <?php 
                        
                        }
                        $l_row = $l_data[0];
                    }
                    ?>
                </select>
            </div>
        </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="">Search by Model </label>
                <select class="form-control" name="l_MO_Name">
                    <option value="<?php echo NULL;?>" >------</option>>
                    <?php  
                    for ($row = 0; $row < count($models); $row++) {
                    ?>
                    <option value="<?php echo $models[$row][1]; ?>"  <?php if($l_MO_Name == $models[$row][1]){ echo "selected";}?> ><?php echo $models[$row][0] ;?></option>
      <?php
}
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
             <div class="form-group">
                <label for="" style="color: #FFFFFF"> .</label>
                <input class="btn btn-primary form-control" type=submit name="SaveRec"  accesskey=Alt-S value="Search Project" >
            </div> 
        </div>
    </div>
</form>
       
       </div>  
      <?php
                           $l_result_project=mysql_query($l_query_project) or die(mysql_error());
                           $l_count_project = mysql_num_rows($l_result_project);
                        
                       ?>
    <div class="row">
    <div class="table-responsive col-md-12 "><?php echo 'Total Projects : ' . $l_count_project;  ?>
    <table  border=1 class=" table table-bordered table-hover  ady-table-content" style="width:100%">
                       
            <tr >
                <th> Project Name </th>
                <th> Project Description</th>
                <th>Project Duration </th>
                <th>Project Model </th>
                <th>No. of people Performing</th>
                <th></th>

            </tr>

           <?php
           if($l_count_project==0)
           {
           ?>
        <tr>
            <td colspan=5>There are no projects under this domain</td>
        </tr>
        <?php   
           }
           else
           {
             while ($l_row_project = mysql_fetch_row($l_result_project))
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
            <td> <?php echo $l_PR_Name?></td>
            <td> <?php echo $l_PR_Desc; ?></td>
            <td><?php echo $l_PR_Duration ?> Weeks</td>
            <td><?php echo $l_row_project[5]; ?></td>
            <td><?php echo $l_PR_count ?> Students</td>
            <td>  
                <?php if($l_PR_id==$l_PR_id_current)  { ?>
                <a class="btn btn-primary" type="button" value="Continue Project" href="<?php echo $l_filehomepath ;?>/SHome.php"> Continue Project</a>
               <?php  } else if(in_array($l_PR_id, $l_PR_id_ended)) {?>
                    <a class="btn btn-primary" type="button" value="View Project" href=<?php echo $l_filehomepath ?>/blank">View Project</a>
                <?php  }  else  { if($l_PR_id_current==NULL)  { ?>
                        <a class="btn btn-primary" name="performproject"  id='<?php echo $l_PR_id ?>' type="button" value="Perform Project" href="<?php echo $l_filehomepath ;?>/ProjectDetails.php?PR_id=<?=$l_PR_id?>">Perform Project</a>
                <?php  } else { ?>
                        <h5> You can perform only one project at a time</h5>
                <?php } }?>
            </td>
        </tr>

            <?php   } } ?>
        </table>
    </div>
</div>
</div>
      <?php include('footer.php');?>
<?php
    
    session_start();session_start();
include ('db_config.php');

    $l_UR_id = $_GET["UR_id"];
    $l_UR_Pass = $_GET["UR_pass"];
    $l_UR_Emailid = $_GET["UR_emailid"];
    $l_UR_Name = $_GET["UR_Name"];
    $l_UR_Semester = $_GET["UR_Semester"];
    $l_UR_USN = $_GET["UR_USN"];
    $l_PR_id = $_GET["PR_id"];
    $l_IT_Name = $_GET["IT_Name"];
    $l_PG_Name = $_GET["PG_Name"];
    $l_ORG_id = $_GET["ORG_id"];
    $l_UR_Type = 'S';
    $l_IT_id = NULL;
    $l_PG_id = NULL;
    
    if($l_UR_id == NULL && $l_UR_pass == NULL && $l_emailid == NULL && $l_PR_id == NULL && $l_IT_Name == NULL && $l_PG_Name == NULL && $l_ORG_id == NULL)
    {
        echo "Error getting the data";
    }
    else
    {
        
        
        $l_ITcheck_query = 'select IT_id from Institutes as IT where IT.IT_Name = "'.$l_IT_Name.'" AND IT.Org_id="'.$l_ORG_id.'"' ;
        $l_ITcheck_result = mysql_query($l_ITcheck_query) or die(mysql_error());
        $l_ITnum_count = mysql_num_rows($l_ITcheck_result);
        if($l_ITnum_count >= 1)
        {
           
            $l_IT_row = mysql_fetch_row($l_ITcheck_result);
            $l_IT_id = $l_IT_row[0];
        }
        else
        {
            $l_get_IT_id = 'select max(IT_id) from Institutes';
            $l_result_check_max_IT = mysql_query($l_get_IT_id);
            $l_row = mysql_fetch_row($l_result_check_max_IT);
            $l_max_IT_id = $l_row[0];
            $l_IT_id = $l_max_IT_id + 1;
            $l_insert_Institute = 'insert into Institutes (IT_id, IT_Name,Org_id) values ('.$l_IT_id .',"'.$l_IT_Name .'","'.$l_ORG_id.'")';
            mysql_query($l_insert_Institute);

        }
        
        
        $l_PGcheck_query = 'select PG_id from Programs  as PG where PG.PG_Name = "'.$l_PG_Name.'" AND PG.Org_id="'.$l_ORG_id.'"' ;
        $l_PGcheck_result = mysql_query($l_PGcheck_query) or die(mysql_error());
        $l_PGnum_count = mysql_num_rows($l_PGcheck_result);
        if($l_PGnum_count >= 1)
        {
            $l_PG_row = mysql_fetch_row($l_PGcheck_result);
            $l_PG_id = $l_PG_row[0];
        }
        else
        {
            $l_get_PG_id = 'select max(PG_id) from Programs';
            $l_result_max_PG = mysql_query($l_get_PG_id);
            $l_row = mysql_fetch_row($l_result_max_PG);
            $l_max_PG_id = $l_row[0];
            $l_PG_id = $l_max_PG_id + 1;
            $l_insert_Programs = 'insert into Programs (PG_id, PG_Name,Org_id) values ('.$l_PG_id .',"'.$l_PG_Name .'","'.$l_ORG_id.'")';
            mysql_query($l_insert_Programs);
        }
        
        
        $l_PGITcheck_query = 'select PG_id, IT_id from Institutes_Program as IP where IP.PG_id = '.$l_PG_id.' and IP.IT_id = '.$l_IT_id.' AND IP.Org_id="'.$l_ORG_id.'"' ;
        $l_PGITcheck_result = mysql_query($l_PGITcheck_query) or die(mysql_error());
        $l_PGITnum_count = mysql_num_rows($l_PGITcheck_result);
        if($l_PGITnum_count == 1)
        {
            $l_PGIT_row = mysql_fetch_row($l_PGITcheck_result);
            $l_PG_id = $l_PG_row[0];
        }
        else
        {
            $l_insert_sql = "insert into Institutes_Program (PG_id, IT_id,Org_id) values (" .$l_PG_id .", ".$l_IT_id .",'".$l_ORG_id."')";
            mysql_query($l_insert_sql);

        }

        
        $l_URcheck_query = 'select UR.UR_id from Users as UR where UR.UR_id = "'.$l_UR_id.'" AND UR.Org_id="'.$l_ORG_id.'"';
        $l_URcheck_result = mysql_query($l_URcheck_query) or die(mysql_error());
        $l_URcheck_count = mysql_num_rows($l_URcheck_result);
        if($l_URcheck_count == 1)
        {
            $l_update_UR = 'update Users set PR_id ='.$l_PR_id .' where UR_id = "'.$l_UR_id.'" AND ORG_id = "'.$l_ORG_id.'"';
            mysql_query($l_update_UR);
            $URid_encode=md5(162).'=='.base64_encode($l_UR_id);
            $ORGid_encode=md5(162).'=='.base64_encode($l_ORG_id);
            //header('loginOrganization.php?UR_id='.$URid_encode.'&ORG_id='.$ORGid_encode.'');
            echo '<script>window.location.href="loginOrganization.php?UR_id='.$URid_encode.'&ORG_id='.$ORGid_encode.'"</script>';

        }
        else
        {
            
            $array_Email=explode('@',$l_UR_Emailid);
            $l_UR_Emailid =$array_Email[0];
            $l_UR_EmailidDomain = $array_Email[1];
            
            $array_Name=explode(' ',$l_UR_Name);
            $l_count=count($array_Name);
            
            if($l_count == 1)
            {
                $l_UR_FirstName = $array_Name[0];
                $l_UR_MiddleName = "";
                $l_UR_LastName ="";
            }
            else if($l_count ==2)
            {
                $l_UR_FirstName = $array_Name[0];
                $l_UR_MiddleName = "";
                $l_UR_LastName =$array_Name[1];
            }
            else if($l_count ==3)
            {
                $l_UR_FirstName = $array_Name[0];
                $l_UR_MiddleName = $array_Name[1];
                $l_UR_LastName =$array_Name[2];
            }
            else if($l_count>3) //Naveen Singh Kumar Mat Pateriya
            {
                $l_UR_FirstName = $array_Name[0];
                $l_UR_MiddleName = "";
                for ($i=1; $i<$l_count-1; $i++)
                {  
                    
                    $l_UR_MiddleName = $l_UR_MiddleName.$array_Name[$i].' ';  
                    
                }
                
                $l_UR_LastName =$array_Name[$l_count-1];
            }
            $timezone = new DateTimeZone("Asia/Kolkata" );
            $date = new DateTime();
            $date->setTimezone($timezone );
            $l_Insert_Datetime = $date->format( 'YmdHi' );
            
            $l_insert_UR = "insert into Users (UR_id, UR_Khufiya, UR_Emailid, UR_EmailidDomain, UR_Type, UR_USN,UR_FirstName, UR_MiddleName, UR_LastName, IT_id, PG_id, PR_id, UR_InsertDate, UR_Semester, UR_RegistrationStatus,Org_id) values ('".$l_UR_id."', '".$l_pass."', '".$l_UR_Emailid."', '".$l_UR_EmailidDomain."' , '".$l_UR_Type."','".$l_UR_USN."','".$l_UR_FirstName."','".$l_UR_MiddleName."','".$l_UR_LastName."',".$l_IT_id.",".$l_PG_id.",".$l_PR_id.",'".$l_Insert_Datetime."','".$l_UR_Semester."','C','".$l_ORG_id."')";
            mysql_query($l_insert_UR);

  	    $URid_encode=md5(162).'=='.base64_encode($l_UR_id);
            $ORGid_encode=md5(162).'=='.base64_encode($l_ORG_id);
          //  header('https://zaireprojects.com/login02.php/?UR_id='.$URid_encode.'&ORG_id='.$ORGid_encode.'');
echo '<script>window.location.href="loginOrganization.php?UR_id='.$URid_encode.'&ORG_id='.$ORGid_encode.'"</script>';


        }

    }

?>


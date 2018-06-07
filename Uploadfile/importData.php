<?php
//load the database configuration file
include 'dbConfig.php';


if(isset($_POST['importSubmit'])){
    
    //validate whether uploaded file is a csv file
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv','application/excel','application/xlsx', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            //skip first line
            fgetcsv($csvFile);
            //line[1]=naveen@adynor.com
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                //check whether member already exists in database with same email
             $prevQuery = "SELECT UR_RegistrationStatus FROM Users WHERE CONCAT(UR_Emailid,'@',UR_EmailidDomain) = '".$line[1]."'";
              
               $prevResult = $db->query($prevQuery);
                if($prevResult->num_rows > 0){

   $db->query("UPDATE Users SET UR_Phno = '".$line[2]."',UR_DOB = '".$line[3]."',PR_id=".$line[4]." WHERE CONCAT(UR_Emailid,'@',UR_EmailidDomain) = '".$line[1]."'");
                }else{
                  
$l_Emailid = $line[1]; 
$array_Email=explode('@',$l_Emailid);
$l_UR_Emailid =$array_Email[0];
$l_UR_EmailidDomain = $array_Email[1];     
              

$array_Name=explode(' ',$line[0]);
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
              
$sql="INSERT INTO Users(UR_id,UR_Emailid,UR_EmailidDomain,UR_Phno,UR_DOB,UR_RegistrationStatus,UR_Khufiya,UR_FirstName,UR_MiddleName,UR_LastName,UR_InsertDate,UR_Type,IT_id,PG_id, UR_PR_Type,PR_id) VALUES ('".$line[1]."','".$l_UR_Emailid."','".$l_UR_EmailidDomain."','".$line[2]."','".$line[3]."','B','".md5($line[3])."','".$l_UR_FirstName."','".$l_UR_MiddleName."','".$l_UR_LastName."','".$l_Insert_Datetime."','S',52,1,'C',".$line[4].")";//line[0]=Name line[1]=email line[2]=phone line[3]=DOB
                    $db->query($sql);
                }
            }
           
            //close opened csv file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

//redirect to the listing page
header("Location: home.php".$qstring);

?>
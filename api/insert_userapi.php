<?php
    
    function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();
        
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                
                curl_setopt($curl, CURLOPT_PUT, 0);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        
        curl_close($curl);
        
        return $result;
    }
    
    $call = CallAPI("GET","https://zaireprojects.com/api/api.php?rquest=users|".md5('zprojects')."",0);
    

    $decoded= json_decode($call);
    echo "<br/><br/>";
   
    $o_connection = mysql_connect('localhost','zairepro_dbuser', '4dyn0rtech!');
    mysql_select_db('zairepro_Projectory');

    $count=count($decoded);
    for($i=0;$i<$count;$i++)
    {
     
  
       echo "<br>";
       // print_r($decoded[$i]);
       echo "<font color=green>User id:</font>".$decoded[$i]->UR_id."<br>";
       echo "Name:".$decoded[$i]->UR_FirstName.' '.$decoded[$i]->UR_LastName."<br>";
       echo "<font color=red> Email id:</font>".$decoded[$i]->UR_emailid.'@'.$decoded[$i]->UR_emailidDomain;
     
       $Check_query="Select UR_id from Users Where UR_id='".$decoded[$i]->UR_id."'";
        echo $Check_query;
       
       $l_query= mysql_query($Check_query);
       $count=mysql_num_rows($l_query);
         echo $count;
         
          if($count==0)
          {
          
        $token= md5(time().$decoded[$i]->UR_emailid.'@'.$decoded[$i]->UR_emailidDomain);  
    
       $insert_query="insert into Users(UR_id,UR_Tokenid) values('".$decoded[$i]->UR_id."','".$token."')"; 
      
      echo $insert_query;
      
      mysql_query($insert_query);
        
       CallAPI("PUT","https://zaireprojects.com/api/api.php?rquest=updateUsers|".md5('zprojects')."&tokenid=".$token."&URid=".$decoded[$i]->UR_id,0);
    
       
        }
        else if($count==1)
        {
        
       $token= md5(time().$decoded[$i]->UR_emailid.'@'.$decoded[$i]->UR_emailidDomain);  
       
       $Update_query="UPDATE  Users SET UR_Tokenid ='".$token."' WHERE UR_id='".$decoded[$i]->UR_id."'";
       
        echo $Update_query;
       mysql_query($Update_query);
       
       CallAPI("PUT","https://zaireprojects.com/api/api.php?rquest=updateUsers|".md5('zprojects')."&tokenid=".$token."&URid=".$decoded[$i]->UR_id,0);
      
       }
    
 }
?>



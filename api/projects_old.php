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
                curl_setopt($curl, CURLOPT_PUT, 1);
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
    
    $call = CallAPI("GET","https://zaireprojects.com/api/api_old.php?rquest=projects|".md5('zprojects')."",0);
    

    $decoded= json_decode($call);
    echo "<br/><br/>";
   
 $count=count($decoded);
    for($i=0;$i<$count;$i++){
  
  if ($decoded[$i]->PR_Price==0)
  {
  $decoded[$i]->PR_Price=Free;
  
  }
  echo "<br>";
   // print_r($decoded[$i]);
   echo "<font color=green>Project Name:</font>".$decoded[$i]->PR_Name."<br>";
     echo "Description:".$decoded[$i]->PR_Desc."<br>";
     echo "<font color=red> Price:</font>".$decoded[$i]->PR_Price;
    
    
    }
  
    
   
?>



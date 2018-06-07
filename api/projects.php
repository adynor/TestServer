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
    
    $call = CallAPI("GET","https://zaireprojects.com/test/api/api.php?rquest=projects|3eb424ee2828c68a796b5136634a1d25",0);
    
echo $call;
   $decoded= json_decode($call);
    echo "<br/><br/>";
   echo  md5('zprojects');
    echo"<pre>";
   print_r($decoded);
    echo"</pre>";
    
 $count=count($decoded);
    for($i=0;$i<$count;$i++){
  
  if ($decoded[$i]->Project_Price==0)
  {
  $decoded[$i]->Project_Price=Free;
  
  }
   $technology=$decoded[$i]->Project_Technology;
  $technologies=implode(',',$technology);
  echo "<br>";
   // print_r($decoded[$i]);
   echo "<font color=green>Project Name:</font>".$decoded[$i]->Project_Name."<br>";
     echo "Description:".$decoded[$i]->Project_Desc."<br>";
     echo "<font color=red> Price:</font>".$decoded[$i]->Project_Price."<br>";
     echo "<font color=blue> Technology:</font>".$technologies."<br>";
    
    
    }
  
  
    
   
?>
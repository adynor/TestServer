<?php
     $username='ankur@adynor.com';
    $password='4dyn0rtech!';
    
    $clientid='570eb91aa88f713507b6';
    $clientsecret='80fb9ec6e8804531dacfb16aebef43818bfd1c96';
echo "Namaste";
$ch = curl_init();
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
curl_setopt($ch, CURLOPT_URL, 'https://github.com/login/oauth/authorize?client_id=570eb91aa88f713507b6&redirect_uri=https://zaireprojects.com/test/gittest/gitpostreturn.php');
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
//curl_setopt($ch, CURLOPT_USERPWD, "570eb91aa88f713507b6");
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
$result=curl_exec ($ch);
curl_close ($ch);

//print_r($status_code);
//print_r($result);
    $arr=json_decode($result);
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
   // echo $result;
?>
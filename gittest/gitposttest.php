<?php
//    $username='ankur@adynor.com';
//    $password='4dyn0rtech!';
    $clientid='570eb91aa88f713507b6';
    $clientsecret='80fb9ec6e8804531dacfb16aebef43818bfd1c96';

$curl = curl_init();
        $post_args = array("clientid"=>  $clientid);
        echo  $dataen = json_encode($post_args);
        
    curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS,  $dataen);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($curl, CURLOPT_URL, "https://github.com/login/oauth/authorize?client_id=570eb91aa88f713507b6");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        
        echo $result = curl_exec($curl);
    
        curl_close($curl);
    $arr=json_decode($result);
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    
    ?>
<?php
include('git_base64_decode.php');
session_start();
echo $username=$_SESSION['login'];
echo "<br>";
echo $password=$_SESSION['access_token'];
//$url='https://api.github.com/user/repos';
//$url='https://api.github.com/repos/gyana2014/testgit56';
///$url='https://api.github.com/repos/gyana2014/testgit/collaborators/naveen0';
//$url='https://api.github.com/repos/gyana2014/testgit/collaborators';
//$url='https://api.github.com/repos/gyana2014/testgit/contents/autoload.php';
$url='https://api.github.com/repos/gyana2014/testgit/contents/error_log2.txt';
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS,  $dataen);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$headers = [
    'Accept:application/vnd.github.mister-fantastic-preview+json'
  // 'Accept: application/vnd.github.VERSION.raw'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
$result=curl_exec ($ch);
if(curl_exec($ch) === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
//echo $result;
curl_close ($ch);
//echo $result;
$array=json_decode($result);
echo "<pre>";
print_r($array);;
echo "</pre>";
echo $array->name."<br>";
echo $array-> content;
echo "<br>";echo "<br>";

echo base64_decode($array-> content);
//print_r(Your_Costum_Base64_Decode_Function($array-> content));
//foreach($array as $arr){

//echo $arr->name."<br>";
//}
?>

<?php
session_start();
echo $username=$_SESSION['login'];
echo "<br>";
echo $password=$_SESSION['access_token'];
if($_POST['submit'])
{
$url='https://api.github.com/repos/adytest/testgit56/contents/'.$_FILES['file']['name'];

$imagedata = file_get_contents($_FILES['file']['tmp_name']);
$base64 = base64_encode($imagedata);

$ch = curl_init($url);
$post_args = array("path"=> "hello1.php","message"=>"my commit message","content"=>$base64,"sha"=> "d1824ac629ed90b4658b4a10c12ac27af560b52d"); 
$dataen = json_encode($post_args);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
curl_setopt($ch, CURLOPT_POSTFIELDS, $dataen);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$headers = array();
$headers[] = 'Authorization: token ' . $_SESSION['access_token'];
$headers[] = "Content-Type: application/x-www-form-urlencoded";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

echo "status=".$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code

$result=curl_exec ($ch);
if(curl_exec($ch) === false)
{
    echo 'Curl error: ' . curl_error($ch);
}

curl_close ($ch);
$array=json_decode($result);
echo "<pre>";
print_r($array);
echo "</pre>";
}
?>


<form action="" method="post" enctype="multipart/form-data">
<table  border=1 class="ady-table-content" style="width:100%">
<tr>
<th colspan="2" style="">Upload File </th>
</tr>
<tr>
<td>File Name</td>
<td>
<input  type="file" name="file" id="file">
</td>
</tr>
<tr>
<td colspan=2>
<center> <input type="submit" name=submit value = "Submit Document"></center>   </td>
</tr>
</form>

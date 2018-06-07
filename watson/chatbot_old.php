<?php
session_start();
$username='01ef20cd-ae90-4bce-9a27-5d82283ff2bc';
$password='nzGyeFP2dHYH';
$URL='https://gateway.watsonplatform.net/conversation/api/v1/workspaces/bbc03798-dd9d-474b-912f-49522f70fa33/message?version=2017-05-26';
$_POST = json_decode(file_get_contents('php://input'), true);
$textdata=$_POST['textdata'];
 $g_conversationid=$_SESSION['g_conid'];
  $g_conversationnodeid=$_SESSION['g_convnodeid'];

$dialog_turn_counter=$_SESSION['d_turn_count'];
$dialog_request_counter=$_SESSION['d_request_count'];



if(!isset($g_conversationid) && !isset($g_conversationnodeid)){
    
   // echo "entered";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$URL);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$headers = array();
$headers[]= "Content-Type:application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
$indata[]=array();
$indata[input][text]=$textdata;
$data1 = json_encode($indata);

curl_setopt($ch, CURLOPT_POSTFIELDS,$data1 );
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$result = curl_exec($ch);
$data= json_decode($result, true);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
curl_close ($ch);

$converid = $data[context][conversation_id];
$converNodeid = $data[context][system][dialog_stack][0][dialog_node];
$abc=array();

$textyou = $data[input][text];
$text = $data[output][text][0];
$abc['you']=$textyou;
$abc['chatbot']=$text ;
$abc['convid']=$converid;
$abc['convnodeid']=$converNodeid;
echo json_encode($abc);

$_SESSION['g_conid']=$converid;
$_SESSION['g_convnodeid']=$converNodeid;
//$response=array('you':'$textyou','chatbot':'$text');
//$returndata = jscon_encode($response);
//echo $returndata;
 }else{

if($g_conversationnodeid!="Node3"){
if($dialog_turn_counter==""){$dialog_turn_counter=1;}else{$dialog_turn_counter++;}
if($dialog_turn_counter==""){$dialog_request_counter=1;}else{$dialog_request_counter++;}

$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL,$URL);
curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
$headers = array();
//$headers[] = "Content-Type: application/x-www-form-urlencoded"; 

$headers[]= "Content-Type:application/json";
curl_setopt($ch1, CURLOPT_HTTPHEADER,$headers);

$indata2[]=array();
$indata2[input][text]=$textdata;
//$indata2[input][text]='i want to do a projects';
$indata2[context][conversation_id]=$g_conversationid;
$indata2[context][system][dialog_stack][0][dialog_node]=$g_conversationnodeid;
$indata2[context][system][dialog_turn_counter]=$dialog_turn_counter;
$indata2[context][system][dialog_request_counter]=$dialog_turn_counter;

$data2= json_encode($indata2);
//echo '<pre>';
//print_r($data2);

//echo '</pre>';
curl_setopt($ch1, CURLOPT_POSTFIELDS,$data2 );
curl_setopt($ch1, CURLOPT_POST, 1);
curl_setopt($ch1, CURLOPT_USERPWD, "$username:$password");

$result = curl_exec($ch1);

$data= json_decode($result, true);
//echo '<pre>';
//print_r($data);

//echo '</pre>';

if (curl_errno($ch1)) {
    echo 'Error:' . curl_error($ch1);
}

curl_close ($ch1);

$converid = $data[context][conversation_id];
$converNodeid = $data[context][system][dialog_stack][0][dialog_node];
$abc=array();
$textyou = $data[input][text];
$text = $data[output][text][0];

if($g_conversationnodeid=="Node2"){
$_SESSION['project']=$textyou;
$a=$_SESSION['project'];
}
$abc['you']=$textyou;
$abc['chatbot']=$text ;
$abc['convid']=$converid;
$abc['convnodeid']=$converNodeid;
if(isset($a)){
$abc['hreflink']=$a;
}
$_SESSION['g_conid']=$converid;
$_SESSION['g_convnodeid']=$converNodeid;
echo json_encode($abc);

}

}
?>



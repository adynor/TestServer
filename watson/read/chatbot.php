<?php
session_start();
$username='516015a8-8572-4af3-b27f-ff1dc0752ba9';
$password='cdInhqJTKIyL';
$URL='https://gateway.watsonplatform.net/conversation/api/v1/workspaces/42b0c33d-e7fb-4544-b642-4b7cd2c6c0df/message?version=2016-09-20';

// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/

$_POST = json_decode(file_get_contents('php://input'), true);
$textdata=$_POST['textdata'];
$g_conversationid=$_SESSION['g_conid'];
 $g_conversationnodeid=$_SESSION['g_convnodeid'];

$dialog_turn_counter=$_SESSION['d_turn_count'];
$dialog_request_counter=$_SESSION['d_request_count'];

if(!isset($g_conversationid) && !isset($g_conversationnodeid)){
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

 }else{

if($g_conversationnodeid!="EngineProblems"){
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

if($g_conversationnodeid=="EngineProblems"){
$_SESSION['project']=$textyou;
$a=$_SESSION['project'];
}
else if($g_conversationnodeid=="Cooling"){
$_SESSION['project']=$textyou;
$a=$_SESSION['project'];
}
else if($g_conversationnodeid=="AboutEngine"){
$_SESSION['project']=$textyou;
$a=$_SESSION['project'];
}
if($g_conversationnodeid=="Restart"){
    $a='Hello How would you like me to help you?';
}
$abc['you']=$textyou;
$abc['chatbot']=$text ;
$abc['convid']=$converid;
$abc['convnodeid']=$converNodeid;
if(isset($a)){
    $text=$a;
    $que=$text;
$etext=str_replace(" ","+",$text);
  $ch = curl_init();
 $url="https://d03ab035-3aef-46aa-afef-ad27c2028655:fcl8xLzEhckS@gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/sca52c8bb2_ba0e_41b8_854a_6935b277b6af/solr/engine_collection/select?q=".$etext."&wt=json&fl=id,body,title";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);

$return = curl_exec ($ch);

if(curl_error($ch)){
    echo 'Request Error:' . curl_error($ch);
}else{
    //echo 'no eror';
}

curl_close ($ch);
$result = json_decode($return,true);
//echo "<pre>";
//print_r($result['response']);
//echo "</pre>";
$data=$result['response'];

$l_count_project = count($data['docs']);    
   if($l_count_project>0) {
      foreach($data['docs'] as $docs)
    {
  
  $abc['hreflink']= $docs['title'].'-'.$docs['body'];
  $abc['que']=$que;
    }
       
   }
    
}
$_SESSION['g_conid']=$converid;
$_SESSION['g_convnodeid']=$converNodeid;
echo json_encode($abc);
}
//

}
?>



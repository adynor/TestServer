<style>

.shadow{
-webkit-box-shadow: 7px 10px 14px 1px rgba(148,204,242,1);
-moz-box-shadow: 7px 10px 14px 1px rgba(148,204,242,1);
box-shadow: 7px 10px 14px 1px rgba(148,204,242,1);
}

</style>


<?php
@session_start();

 include('Crypto.php')?>

<?php
       
	error_reporting(0);
	//$workingKey='4770546A6784D9CF8115713CBE2A4089'//Working Key should be provided here.
	$workingKey='4770546A6784D9CF8115713CBE2A4089';
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
	}
	
	/* Database Connection */
	//$o_connection = mysql_connect('localhost','zairepro_test','test@123!');
	//mysql_select_db('zairepro_Projectory_test');
	include('../db_config.php');
	$l_PR_id=0;
	$l_PR_name="";
	$l_UR_id="";
	$l_UR_amount=0;
	
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		//echo "<pre>";
		//print_r($information);
	    	//echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
	    	//exit();
	    	if($information[0]=="merchant_param2"){
	    	  $l_PR_id = $information[1];
	    	    }
	    	else if($information[0]=="merchant_param1"){
	    	$l_team= $information[1];
	    	    }
	    	  else if($information[0]=="merchant_param3"){
	    	 $l_UR_id= $information[1];
	    	    }
	    	    else if($information[0]=="merchant_param4"){
	    	  $l_org_id= $information[1];
	    	    }
	    	    else if($information[0]=="merchant_param5"){
	    	   $l_mentor= $information[1];
	    	    }
	    	else if($information[0]=="amount"){
	    	   $l_UR_amount = $information[1];
	    	    }
	    	 else if($information[0]=="order_id"){
	    	 $l_order_id = $information[1];
	    	    }
	    	 
	}
	
	
	echo "<center>"	;
	if($order_status==="Success")
	{
$timezone = new DateTimeZone("Asia/Kolkata");
$date = new DateTime();
$date->setTimezone($timezone);
$l_Datetime = $date->format('YmdHi');
	echo "<br ><div style='color: green;
    font-size: 18px;
    background-color: rgba(154, 205, 50, 0.42);
    width: 81%;
    height: auto;
    padding-top: 12px;
    padding-bottom: 7px;
    border: 2px solid greenyellow;
'> Thank you for your payment. Your transaction is successful. </div>";




echo "<br>";
$l_query_insert_PR_id='insert into Project_Applications(UR_id,PR_id,PP_ApplicationDate,Org_id) values ("'.$l_UR_id.'",'.$l_PR_id.','.$l_Datetime.',"'.$l_org_id.'")';

mysql_query($l_query_insert_PR_id);
echo "<br>";
 $l_query_insert_PR_Pay='insert into Payment_Access(UR_id,PR_id,PA_Date, PA_Amount,PA_OrderNo,Org_id) values ("'.$l_UR_id.'",'.$l_PR_id.','.$l_Datetime.','.$l_UR_amount.',"'.$l_order_id.'","'.$l_org_id.'")';

mysql_query($l_query_insert_PR_Pay);

$l_Tque='UPDATE Teams SET UR_id_Mentor="'.$l_mentor.'" WHERE TM_id='.$l_team.'';
mysql_query($l_Tque);
       
       }
	else if($order_status==="Aborted")
	{
		echo "<br ><span style='color:orange;font-size:18px;'>Thank you  us.We will keep you posted regarding the status of your order through e-mail</span>";
	$url= 'https://www.zaireprojects.com/test/PaymentMentorhr.php';
	}
	else if($order_status==="Failure")
	{
		echo "<br><span style='color:red;font-size:18px;'>Thank you .However,the transaction has been declined.</span>";
	$url= 'https://www.zaireprojects.com/test/PaymentMentorhr.php';
	}
	else
	{
		echo "<br><span style='color:red;font-size:18px;'>Security Error. Illegal access detected</span>";
	
	$url= 'https://www.zaireprojects.com/test/PaymentMentorhr.php';
	}


	
?>
<br /><br />
<div >
	<table class="shadow" cellpadding="10" style="background: antiquewhite;
    border: 4px solid rgba(0, 102, 255, 0.55);
    color: #0085FF;
    padding: 10px;">
	<tr><td>Order Id</td><td><?php echo $l_order_id;?></td></tr>
	<tr><td>User Id</td><td><?php echo $l_UR_id ;?></td></tr>
	<tr><td>Price</td><td><?php echo $l_UR_amount; ?></td></tr>
	<tr><td>Oder Status</td><td><?php echo $order_status; ?></td></tr>
	</table> </div>
	<br><a style="color: hsl(231, 72%, 92%);
    background-color: rgba(0, 54, 134, 0.96);
    width: 100%;
    height: 50%;
    padding: 9px;
    border-radius: 12px; text-decoration:none;" href="https://www.zaireprojects.com/test/SHome.php">Back To Home </a>
        </center>
<html>
<head>
<title> Non-Seamless-kit</title>
</head>
<body>
<center>

<?php 
//print_r($_POST);
//exit();
include('Crypto.php')?>
<?php 

	error_reporting(0);
	
	$merchant_data='';
	//$working_key='4770546A6784D9CF8115713CBE2A4089';//Shared by CCAVENUES
	//$access_code='AVXP63DA41AJ26PXJA';//Shared by CCAVENUES
	$working_key='4770546A6784D9CF8115713CBE2A4089';//Shared by CCAVENUES
	$access_code='AVXP63DA41AJ26PXJA';
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.$value.'&';
	}

	$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

?>
<form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>
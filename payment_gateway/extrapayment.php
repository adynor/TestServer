<?php
session_start();
//print_r($_SESSION);

$l_Amount= $_SESSION['payment'];
$l_PR_id_pay=$_SESSION['g_PR_id_pay'];
$l_UR_id=$_SESSION['g_UR_id'];
$l_PR_Name_pay=$_SESSION['g_PR_Name_pay'] ;
$l_org_id=$_SESSION['g_Org_id'];
$l_UR_PR_Type=$_SESSION['l_User_PR_Type'];


/* Database connection*/
echo "<center style='color:red'>Please Wait..........</center>";
//$o_connection = mysql_connect('localhost','zairepro_test','test@123');
//mysql_select_db('zairepro_Projectory_test');
include('../db_config.php');
//Select the User Details for billing
 echo $sql='select UR_FirstName,UR_MiddleName,UR_LastName,UR_Emailid,UR_EmailidDomain,UR_Phno,UR_Address,UR_City,UR_State,UR_Country,UR_Zipcode from Users WHERE UR_id="'.$l_UR_id.'" AND Org_id="'.$_SESSION['g_Org_id'].'"';
$l_query=mysql_query($sql);
$l_user_result=mysql_fetch_array($l_query);

extract($l_user_result);
/*Generate the order id*/
function generateRandomString($length =4) {
   $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;

}


while(true){

$timezone = new DateTimeZone("Asia/Kolkata" );
 $date = new DateTime();       
 $date->setTimezone($timezone );
 $cusstring=generateRandomString();
 $time=$date->format('YmdHis');
 $data=99999999999999-$time;
$zporderid='ZP'.$data.$cusstring;
$query_order_id=mysql_query('SELECT PA_OrderNo FROM  Payment_Access WHERE PA_OrderNo="'.$zporderid.'"');
$count_order_id=mysql_num_rows($query_order_id);

    if($count_order_id==0){
        break;
    }

}
//Order id end
?>

<script>
	window.onload = function() {
		var d = new Date().getTime();
		document.getElementById("tid").value = d;


	};
</script>

<form method="post" id="lol" name="customerData" action="ccavRequestHandler.php">
<table style="display:none;">
  
   <tr><td>TID:</td><td><input type="text" name="tid" id="tid" /></td> </tr>
   <tr><td>Merchant Id	:</td><td><input type="hidden" name="merchant_id" value="87149" /></td></tr>
   <tr><td>Order ID:</td><td><input type="text" name="order_id" value="<?php echo $zporderid; ?>" /></td></tr>
   <tr><td>Amount	:</td><td><input type="text" name="amount" value="<?php echo $l_Amount; ?>"/></td></tr>
   <tr><td>currency	:</td><td><input type="hidden" name="currency" value="INR"/></td></tr>
   <tr><td>Redirect Url	:</td><td><input type="hidden" name="redirect_url" value="https://zaireprojects.com/test/payment_gateway/extrapaymentresponse.php" /></td></tr>       
   <tr><td>Cancel Url	:</td><td><input type="hidden" name="cancel_url" value="https://zaireprojects.com/test/PaymentMentorhr.php" /></td></tr>
    <tr><td>language:</td><td><input type="hidden" name="language" value="EN" /><td></tr>
    <tr><td colspan="2">Billing information(optional):</td></tr>
    <tr><td>Billing Name	:</td><td><input type="text" name="billing_name" value="<?php echo $UR_FirstName.' '.$UR_MiddleName.' '.$UR_LastName ;?>"/></td></tr>
    <tr><td>Billing Address	:</td><td><input type="text" name="billing_address" value="<?php echo $UR_Address ;?>"/></td></tr>
    <tr> <td>Billing City	:</td><td><input type="text" name="billing_city" value="<?php echo $UR_City ;?>"/></td></tr>
    <tr><td>Billing State	:</td><td><input type="text" name="billing_state" value="<?php echo $UR_State ;?>"/></td></tr>
    <tr><td>Billing Zip	:</td><td><input type="text" name="billing_zip" value="<?php echo $UR_Zipcode ;?>"/></td></tr>
    <tr> <td>Billing Country	:</td><td><input type="text" name="billing_country" value="<?php echo $UR_Country ;?>"/></td></tr>
    <tr><td>Billing Tel	:</td><td><input type="text" name="billing_tel" value="<?php echo $UR_Phno ;?>"/></td></tr>
    <tr><td>Billing Email	:</td><td><input type="text" name="billing_email" value="<?php echo $UR_Emailid.'@'.$UR_EmailidDomain;?>"/></td></tr>

    <tr><td colspan="2">Shipping information(optional)</td></tr>
    <tr>
       <td>Shipping Name	:</td><td><input type="text" name="delivery_name" value="<?php echo $UR_FirstName.' '.$UR_MiddleName.' '.$UR_LastName ;?>"/></td>
    </tr>
    <tr>
       <td>Shipping Address	:</td><td><input type="text" name="delivery_address" value="<?php echo $UR_Address ;?>"/></td>
    </tr>
    <tr>
       <td>shipping City	:</td><td><input type="text" name="delivery_city" value="<?php echo $UR_City ;?>"/></td>
    </tr>
    <tr>
       <td>shipping State	:</td><td><input type="text" name="delivery_state" value="<?php echo $UR_State ;?>"/></td>
    </tr>
    <tr>
       <td>shipping Zip	:</td><td><input type="text" name="delivery_zip" value="<?php echo $UR_Zipcode ;?>"/></td>
    </tr>
    <tr>
       <td>shipping Country	:</td><td><input type="text" name="delivery_country" value="<?php echo $UR_Country ;?>"/></td>
    </tr>
    <tr>
       <td>Shipping Tel	:</td><td><input type="text" name="delivery_tel" value="<?php echo $UR_Phno ;?>"/></td>
    </tr>
    <tr>
       <td>Merchant Param1 PR_Name:</td><td><input type="text" name="merchant_param1" value="<?php echo $_SESSION['g_TM_id'] ;?>"/></td>
    </tr>
    
    <tr>
       <td>Merchant Param2 PR_id:</td><td><input type="text" name="merchant_param2" value="<?php echo $l_PR_id_pay ;?>"/></td>
    </tr>
     <tr>
       <td>Merchant Param3 UR_id:</td><td><input type="text" name="merchant_param3" value="<?php echo $l_UR_id ;?>"/></td>
    </tr>
     <tr>
       <td>Merchant Param4 Org_id:</td><td><input type="text" name="merchant_param4" value="<?php echo $l_org_id ;?>"/></td>
    </tr>
      <tr>
       <td>Merchant Param5 mentor:</td><td><input type="text" name="merchant_param5" value="<?php echo $_SESSION['mentor'] ;?>"/></td>
    </tr>
   
                                                                                                                                          
    <tr>
       <td></td><td><INPUT TYPE="submit" value="CheckOut"></td>
</tr>
</table>
 </form>
 
<script type="text/javascript">document.getElementById('lol').submit();</script>
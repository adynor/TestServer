<?php   

include ('db_config.php');
include ('header.php');

?>
<br><br><br><br>
<div class="container" >
       <div class="row" style="width:100%;">
           <div class=" ady-row">

<?php


print('<body>');


$l_UR_Type = $_SESSION['g_UR_Type'];
$l_UR_id = $_SESSION['g_UR_id'];

if(empty($l_UR_id) || $l_UR_Type!='T')
{
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as an authorised person. Please login correctly")
        window.location.href="'.$l_filehomepath.'/login"; </script> ';

        print($l_alert_statement );
}

if(isset($_POST['Submit']))
    {
        $l_Message = $_POST['l_UR_Message'];
        
        $l_mail_query = 'Select UR.UR_Emailid, UR.UR_EmailidDomain from Users as UR ';
        $l_mail_res = mysql_query($l_mail_query);
        
        $l_webMaster    = 'support@zaireprojects.com';    
        $l_subject = "Alert Message";
        $l_headers2 = "From: $l_webMaster\r\n";
        $l_headers2 .= "Content-type:  text/html\r\n";

        while($l_mail_row = mysql_fetch_row($l_mail_res))
        {
          $to  = array($l_mail_row[0] .'@'. $l_mail_row[1]);
            sendmail($to,$subject,$l_Message);
           //mail( $l_UR_mailid, $l_subject, $l_Message, $l_headers2);
        
        }
    }
   

print('<div  style="clear:left" style="border: 1px solid #0A9CF1;
    border-radius: 16px;">');
print('<form>');

print('<table class="ady-table-content"  style="width:100%; 
 border: 1px solid #0A9CF1;
    border-radius: 16px;">');
print ('<tr>');

print ('<th font-size:18px; font-weight:bold; text-align:center">Send Message</th>');
print ('</tr>');
print ('<tr>');
print ('<td> <textarea class="form-control ady-form" name= l_UR_Message ></textarea>   </td>');
print ('<tr>');
print ('<tr>');
print ('<td style="text-align:center;"> <input class="btn-primary ady-cus-btn" type="submit" name= Submit value=send >   </td>');
print ('</tr>');
print('</table>');
print('</form');
print('</div');
print('</body>');

?>

                   </div></div></div>
<?php include('footer.php')?>               
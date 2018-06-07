<?php   
ob_start();
require_once('tcpdf/tcpdf.php');
include ('db_config.php');

function fetch_data(){
$l_select_sql = 'Select UR.UR_id, UR.UR_FirstName, UR.UR_MiddleName, UR.UR_LastName, UR.UR_Emailid, UR.UR_EmailidDomain,PG.PG_Name,IT.IT_Name from Users as UR, Institutes as IT, Programs as PG where UR.UR_Type = "S" and UR.UR_RegistrationStatus = "P" and UR.PG_id = PG.PG_id and UR.IT_id = IT.IT_id and UR.IT_id = IT.IT_id and UR.PG_id<>"" and UR.IT_id<>""' ;

$l_result = mysql_query($l_select_sql);
$l_UR_count = mysql_num_rows($l_result);
  $output='';  
   
  if($l_UR_count>0){
   
   
while ($l_row = mysql_fetch_row($l_result))
        {
 $l_studentName=$l_row[1].' '.$l_row[2].' '.$l_row[3];
 $l_PG_Name = $l_row[6];
$l_IT_Name = $l_row[7];

$output.='<tr>
<td>'.$l_studentName.'</td>
<td>'.$l_row[4].'@'.$l_row[5].'</td>
<td>'.$l_IT_Name.'</td>
<td>'.$l_PG_Name.'</td>
</tr><hr>';            
        
     }
  
  
   }
   return $output;
}

if(isset($_POST['getpdf'])){
$content= '';
//require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('helvetica', '', 9);

$content.="<h3 align='center'>Export HTML Table to PDF Document</h3><table cellspacing='1' cellpadding='4' border='1' border='1' style='width:100%'>
<tr border=1> 
   <th border=1>Student<br>Name</th>
   <th>Student<br>Email Id</th>
   <th>Institute<br>Name</th>
   <th>Programme<br>Name</th>   
   <th>Select<br>Student</th>
        
</tr><hr>";
$pdf->AddPage();
$content .= fetch_data();
$content .="</table>";
$pdf->writeHTML($content, true, 0, true, 0);
$pdf->lastPage();
$pdf->Output('DounloadPdf.pdf', 'D');

}
?>
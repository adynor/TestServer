 <?php 
 include('header.php');
 include('db_config.php');
 function getDateTime(){
 $timezone = new DateTimeZone("Asia/Kolkata" );
          $date = new DateTime();
          $date->setTimezone($timezone );
     return $currentdatetime = $date->format('Ymd');
 }
 
 function getDayvalue($date_m){
  $days=array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'); 
   return $currentvalue= array_search(date('l', strtotime($date_m)),$days);
 }
 function getMentor(){
  $sql="SELECT UR_id_Mentor FROM Teams WHERE TM_id=".$_SESSION['g_TM_id']."";
  $que=mysql_query($sql);
   $row=mysql_fetch_array($que);
   return $row['UR_id_Mentor'];
  }
  function getUserslots($currentday,$val=NULL){
  
     if($currentday > 1){
        $c_d =$currentday-1;
        //$dec=getDateTime()-$c_d;
       $dec= date('Ymd',strtotime(getDateTime()) - (24*3600*$c_d));
     } else{
       $dec=getDateTime();
     }
     if($currentday < 7 ){
      $c_e =7-$currentday;
      //$inc=getDateTime()+$c_e;
      $inc= date('Ymd',strtotime(getDateTime()) + (24*3600*$c_e));
     } else{
       $inc=getDateTime();
     }

  $sql='SELECT BS_Date,BS_Start_Time,BS_End_Time,BS_TM_id,BS_Status FROM Booking_Slots WHERE '.$dec.' <= BS_Date  AND BS_Date <='.$inc.' AND BS_Mentor_id="'.getMentor();
  if($val != NULL){
    $sql.='" AND BS_TM_id="'.$_SESSION[g_TM_id] ;
  } else {
    $sql.='" AND BS_TM_id <>"'.$_SESSION[g_TM_id] ;
  }
 $sql.='" AND (BS_Status="P" OR BS_Status="A")' ;
  $que=mysql_query($sql);
  $slots=array();
  $slot_status=array();
  $i=0;
   While($row=mysql_fetch_array($que)){
          $day=getDayvalue($row['BS_Date']);
      //echo  $day=$row['BS_Date']."<br>";
        $value=$row[1].','.$row[2];
       // $slots[4]=array();
       //array_push($slots[4],$value);
       $slots[$day][$i]=$value;
       $slot_status[$day][$i]=$row['BS_Status'];
       $i++;
      
   }
    if($val != NULL){
     return array($slots,$slot_status);
 
  } else {
     return $slots;
 
  }
  
  }
  function getSlot($day,$currentday,$userslots,$ownslots,$countownslot,$ownslots_status){
 // print_r($userslots);
  $sql="SELECT MC_StartTime,MC_EndTime FROM Mentor_Calendar WHERE UR_id='".getMentor()."' AND MC_DayNo=".$day."" ;
  $que=mysql_query($sql);
  $slots=array();
 While($row=mysql_fetch_array($que)){
 //print_r($row);
 $a=$row['MC_StartTime'];
 $b=$row['MC_EndTime'];
 $hours=array('12:00 AM','01:00 AM','02:00 AM', '03:00 AM', '04:00 AM', '05:00 AM', '06:00 AM', '07:00 AM', '08:00 AM','09:00 AM','10:00 AM','11:00 AM','12:00 PM', '01:00 PM','02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM', '06:00 PM', '07:00 PM', '08:00 PM','09:00 PM','10:00 PM','11:00 PM','12:00 AM');
	 for($a;$a < $b;$a++){
	//print_r($userslots);
	 $c=$a+1;
	  $d= $a.','.$c;
	 $sol='<span ';
	 if(count($userslots) > 0){
           if(in_array($d,$userslots)){
             $sol.=' class="label btn  label-danger"';
             $sol.='style="text-decoration: none,cursor:default!important" disabled="disabled"';
           } 
	 }
	 $abcd=NULL;
	  if(count($ownslots) > 0){
           if(in_array($d,$ownslots)){
           if($ownslots_status == 'A'){
             $sol.='class="label btn  label-success"';
             $abcd=1;
             }
             else if($ownslots_status == 'P'){
             $abcd=1;
             $sol.='class="label btn  label-warning"';}
             $sol.='style="text-decoration: none,cursor:default!important" disabled="disabled"';
             
           } 
	 } 
	
	  if($day >= $currentday){ 
	    if($countownslot > 0 ){
	    //echo $countownslot.">=".$currentday;
	    $sol.=' class="label btn  label-primary "';
	    $sol.='style="text-decoration: none,cursor:default!important" disabled="disabled"';
	    
	    
	    }else{
	    $sol.=' class="label btn  bokslot label-primary "';
	    }
	 } else {
	    $sol.=' class="label btn label-default"';
	    $sol.='style="text-decoration: none,cursor:default!important" disabled="disabled"';
	 }
	// if($day < $currentday){
	 
	 //;
	 //}
	 $sol.='data-status="enable" data-start="'.$a.'"  data-day="'.$day.'" data-end="'.$c.'">'.$hours[$a].'-'.$hours[$c].'</span>';
	 if($abcd==1){
	 $sol.='<span class="glyphicon glyphicon-remove" style="font-size: 15px;
    border: 2px solid red;
    border-radius: 0px 5px 5px 0px;
    position: relative;
    color: red;
    top: 3px;
    left: -1px;" id="removeslot"></span>';
	 }
	 
	 array_push($slots,$sol);
	 }
 }
 return $slots;
 }
 
function setSlot($start,$end,$Bday){

$Cday=getDayvalue(getDateTime());
if($Cday >= $Bday){
   $accday=$Cday-$Bday;
    //$Bdate=getDateTime()-$accday;
    $Bdate = date('Ymd',strtotime(getDateTime()) - (24*3600*$accday));

} else if($Cday <= $Bday){
    $accday=$Bday-$Cday;
    //$Bdate=getDateTime()+$accday;
    $Bdate = date('Ymd',strtotime(getDateTime()) + (24*3600*$accday));

}
          $Cdate=getDateTime();
          $mentor=getMentor();
          $student=$_SESSION['g_UR_id'];
          $Team=$_SESSION['g_TM_id'];
  $sql='insert into Booking_Slots (BS_Start_Time,BS_End_Time,BS_Date,BS_Mentor_id,BS_ST_id,BS_TM_id) values('.$start.','.$end.','.$Bdate.',"'.$mentor.'","'.$student.'",'.$Team.')';
 mysql_query($sql);

}
switch ($_REQUEST['bookfun']) {
    case 1: 
    setSlot($_POST['startslot'],$_POST['endslot'],$_POST['day']);
        break;
    
    default:
       
?>

<br><br><br>

  <?php
  
  
 
  
  ?>

</div>


</script>
 <div class="col-md-8 col-md-offset-2">
      <h3 style="color:blue;"><b>Availability Times</b></h3>
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4 col-sm-4 text-center"><strong> Days </strong></div>
            <div class="col-md-8 col-sm-8 text-center"><strong> Book Slot </strong></div>
        </div>
         <?php 
        $days=array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
          $cdate=getDateTime();
        $currentvalue=getDayvalue($cdate);
        $myslot=getUserslots($currentvalue,1);
        //print_r($myslot);
       $countownslot=count($myslot[0]);
         //$currentvalue=1;
         //echo  $currentvalue= 'sfsd'.array_search(date('l', strtotime('20161125')),$days);
        // echo "<pre>";
         $userslot=getUserslots($currentvalue);
         // print_r($userslot);
         // echo "</pre>";
                  for($j=1; $j<=7;$j++){?>
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-4 col-sm-4 col-xs-4"> 
                <?php echo $days[$j];?>
                <input type="hidden" name="l_mentor_days[]" value="<?php echo $j;?>" >
            </div>
            <div class="col-md-8 col-sm-8 col-xs-8">
            
            <?php
            $rrar=$userslot[$j];
            $ownslots=$myslot[0][$j];
           $ownslots_status=$myslot[1][$j][0];
          
             foreach(getSlot($j,$currentvalue,$rrar,$ownslots,$countownslot,$ownslots_status) as $slot){ ?>
             <?php echo $slot;?>
            <?php } ?>
            
           
             </div>
          
        </div>
         <?php }
         }
         ?>
 
 
 
 <script>
 $(function(){
 
  $('.bokslot').bind('click',function(event){
 alert();
 	$(this).addClass('label-warning');
  	$(".bokslot").unbind( "click","handler" );
 	$(".bokslot").css({"text-decoration": "none","cursor": "default"});
 	$(".bokslot").attr('disabled','disabled');
          $(".bokslot").removeClass('bokslot')

  
 
 var start = $(this).data('start');
 var end = $(this).data('end');
  var day = $(this).data('day');
 $.ajax({
 url :'BookTheSlot.php?bookfun=1',
 data:{startslot:start,endslot:end,day:day},
 type:'POST',
 success:function(data){

//console.log(data);
 
 }
 });
event.preventDefault();
 });

  $('#removeslot').on('click',function(event){
  alert();
  //event.preventDefault();
 	$('.label-warning').addClass('bokslot');
 	$('.label-success').addClass('bokslot');
 	$('.label-primary').addClass('bokslot');
 	$('.label-warning').addClass('label-primary');
 	$('.label-success').addClass('label-primary');
 	$('.label-warning').removeClass('label-warning');
 	$('.label-success').removeClass('label-success');
  	//$(".bokslot").off("click");
  	$(".bokslot").bind( "click","handler" );
 	$(".bokslot").attr("style", "")
 	$(".bokslot").removeAttr('disabled');
 	$(this).hide();
/*
  
 
 var start = $(this).data('start');
 var end = $(this).data('end');
  var day = $(this).data('day');
 $.ajax({
 url :'BookTheSlot.php?bookfun=1',
 data:{startslot:start,endslot:end,day:day},
 type:'POST',
 success:function(data){

console.log(data);
 
 }
 });*/
event.preventDefault();
 });
 });
 
 
 </script>
<?php include('footer.php')?>
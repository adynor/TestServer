<style>
.scrollToTop{
	width:100px; 
	height:100px;
	padding:10px; 
	text-align:center; 
	background: none;
	font-weight: bold;
	color: blue;
	text-decoration: none;
	position:fixed;
	bottom:0px;
	right:40px;
	display:none;
	
}


.scrollToTop:hover{
	text-decoration:none;
}

.morectnt span {

display: none;

}

      .hamariclass table {
            width: 100%;
        }

        .hamariclass thead, .hamariclass tbody, .hamariclass tr,.hamariclass td,.hamariclass th { display: block; }

       .hamariclass tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }

       .hamariclass thead th {
            height: 65px;

            /*text-align: left;*/
        }

        .hamariclass tbody {
            height: 410px;
            overflow-y: auto;
        }

       .hamariclass thead {
            /* fallback */
        }


       .hamariclass tbody td, thead th {
            width: 50%;
            float: left;
        }
        0em;
}


</style>

<?php
include('header.php');
include ('db_config.php');


print('<div class="row" style="padding:10px"></div><div class="container" >'); 

if(isset($_GET['Advancesearch'])){
$text=$_GET['Advancesearch'];
}else{
$text =$_GET['stext'];
}

?>
<form action="" method="GET">
        <div class="input-group">
  <input style="height: 48px;" class="form-control" value="<?php  echo $text;?>" type="text" name="stext">
  <span class="input-group-addon" ><input class="btn btn-primary" type="submit"    accesskey=Alt-S value="Search Projects" ></span>
</form>       
 </div>
 <br>
 <?php

  $etext=str_replace(" ","+",$text);
  $ch = curl_init();
 $url="https://d03ab035-3aef-46aa-afef-ad27c2028655:fcl8xLzEhckS@gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/sca52c8bb2_ba0e_41b8_854a_6935b277b6af/solr/projectcollection/select?q=".$etext."&wt=json&fl=id";
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
 ?>
       <div class="table-responsive col-md-12 "><h4><span  class="label label-success"> Cognitive Project Recommendation Engine top Results : <?php  echo  $l_count_project;  ?></span></h4>
       
       
        <table border=1 class="ady-table-content hamariclass" style="width:100%">
<?php 


?>

<thead>
    <tr>
<th style="width:40%"> Project Name </th>
<th style="width:60%"> Project Description</th>
 </tr>
  </thead>
 
 <?php                           

if($l_count_project == 0 ){
?>
<tr>
    <td colspan=2>
        There are no projects to show.
    </td>
</tr>
    <?php } else { $i=1;
    foreach($data['docs'] as $docs)
    {
   
    //print_r($docs);
    $sql= "select PR.PR_Name, PR.PR_Short_Desc  from Projects as PR WHERE PR.PR_id=".$docs['id']."";
    $res=mysql_query($sql);
    $r0w=mysql_fetch_row($res);
    ?>
    <tr>
    <td style="width:40%">
        <?php echo $r0w[0];?> &nbsp;<span style= "    float: right;" class="label label-success">Rank
        <?php echo $i;
          ?></span>
        
    </td>
     <td style="width:60%" >
        <?php echo html_entity_decode($r0w[1]); ?>
    </td>
</tr>
<?php $i++; } ?>
                           </table
                           <?php } ?>

</div>


<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});



$(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
	
});



</script>

<script type="text/javascript">
	$(function() {

var showTotalChar = 200, showChar = "Show more", hideChar = "Show Less";

$('.show').each(function() {

var content = $(this).text();

if (content.length > showTotalChar) {

var con = content.substr(0, showTotalChar);

var hcon = content.substr(showTotalChar, content.length - showTotalChar);

var txt= con +  '<span class="dots">......</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="" class="showmoretxt">' + showChar + '</a></span>';

$(this).html(txt);

}

});

$(".showmoretxt").click(function() {

if ($(this).hasClass("sample")) {

$(this).removeClass("sample");

$(this).text(showChar);

} else {

$(this).addClass("sample");

$(this).text(hideChar);

}

$(this).parent().prev().toggle();

$(this).prev().toggle();

return false;

});

});

</script>



      <?php echo '<a href="#" class="scrollToTop"><span class="glyphicon glyphicon-arrow-up" style="color: #34383a; font-size:30px;"> </span></a>'; 
      
     
      include('footer.php');?> <br/><br/>
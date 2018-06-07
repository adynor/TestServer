<?php

    //////////////////////////////////////////////
    // Name            : ATeams_Track
    // Project         : Projectory
    // Purpose         : College admin will insert the project
    // Called By       : login01
    // Calls           :  Aview_SPayments01, aview_newstudent, aview_newGuide, AStudentResults
    // Mod history:
    //////////////////////////////////////////////
    
include ('db_config.php');
include ('header.php');  
?>
 <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.24/angular.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>

body {
    padding:85px;
    font-family:sans-serif;
}
.timeline {
    white-space:nowrap;
    overflow-x: scroll;
    padding:33px 0 10px 0;
    position:relative;
}

.entry {
    display:inline-block;
    vertical-align:top;
    background:rgba(4, 50, 107, 0.54);
    color:#fff;
    padding:10px;
    font-size:15px;;
    text-align:center;
    position:relative;
    border-top:4px solid rgba(4, 50, 107, 0.54);
    border-radius:43px;
    min-width:150px;
    max-width:500px;
}

.entry:after {
content: '';
display: block;
background: rgb(255, 255, 255);
width: 12px;
height: 12px;
border-radius: 6px;
border: 1px solid #2196f3;
position: absolute;
left: 46%;
top: -31px;
margin-left: 1px;
}

.entry:before {
content: '';
display: block;
background: rgb(33, 150, 243);
width: 2px;
height: 17px;
position: absolute;
left: 50%;
top: -21px;
margin-left: 0px;
}

.entry h1 {
    color:#fff;
    font-size:18px;
    font-family:Georgia, serif;
    font-weight:bold;
    margin-bottom:10px;
}

.entry h2 {
    letter-spacing:.2em;
    margin-bottom:10px;
    font-size:14px;
}

.bar {
height: 2px;
background: #2196F3;
width: 100%;
position: relative;
top: 13px;
left: 0;
}
.user_tip_marker .inner_blink{
    display: inline-block;
    width: 12px;
    height: 12px;
    background-color: #00a2ea;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    margin-left: -14px;
    margin-top: -9px;
    border: 3px solid #fff;
     animation: blinker 2s linear infinite;
}


@keyframes blinker {  
  50% { opacity: 0; }
}


.user_tip_marker .blink {
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: -1px;
    margin-top: -1px;
    border: 4px solid #00a2ea;
    border-radius: 50%;
    -webkit-animation: pulsate 2s ease-out infinite;
    -moz-animation: pulsate 2s ease-out infinite;
    -o-animation: pulsate 2s ease-out infinite;
    animation: pulsate 2s ease-out infinite;
}

span{
    border: 0;
    outline: 0;
    vertical-align: baseline;
    background: transparent;
    margin: 0;
    padding: 0;
}
.badgeinfo {
    display: inline-block;
    min-width: 10px;
    padding: 5px 9px;
    margin-right: 3px;
    font-size: 14px;
    font-weight: 7000;
    line-height: 1;
    color: #3375b1;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color: rgb(233, 237, 231);
    border-radius: 10px;
}
.badge {
    display: inline-block;
    min-width: 10px;
    padding: 5px 9px;
    margin-right: 3px;
    font-size: 14px;
    font-weight: 7000;
    line-height: 1;
    color: #e9eef3;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color: rgba(38, 162, 7, 0.86);
    border-radius: 10px;
}
.modal-body {
   overflow: auto;
  max-height: calc(100vh - 60px);
}

body .modal-dialog { /* Width */
    max-width: 100%;
    width: auto !important;
    display: inline-block;
}

.modal-header, .modal-footer {
 flex-grow: 1;
 flex-shrink: 0;
 flex-basis: auto;
}
.ng-cloak { display:none; }
a{cursor: pointer;}

.morectnt span {

display: none;

}

</style>
<div class="row" style="padding:10px"></div>
<div class="row" style="padding:10px"></div>
<div class="container" ng-app="fetch">
    <?php
    //session id to local variables
    $l_UR_id                     = $_SESSION['g_UR_id'];  // For the Communications table we need the from id
   $l_IT_id                      = $_SESSION['g_IT_id'];
    $l_UR_Type                     = $_SESSION['g_UR_Type'];
    //check if the user is logged in and is a college admin
    if(is_null($l_UR_id) || $l_UR_Type!='A')
    {
        $l_alert_statement =  ' <script type="text/javascript">
        window.alert("You have not logged in as the college admin. Please login correctly")
        window.location.href="Signout.php"; </script> ';
        
        print($l_alert_statement );
    }
    
 
    // get the last login date and time
    $l_LastLoginDate_query = 'select  UR_LastLogin from Users where UR_id = "'.$l_UR_id.'" and Org_id="'.$_SESSION['g_Org_id'].'"';
    $l_LastLoginDate = mysql_query($l_LastLoginDate_query) or die(mysql_error());
    $l_Date=mysql_fetch_row($l_LastLoginDate);
    $l_LoginDate_res=$l_Date[0];
    
    $l_LoginDate_res= date("d-M-Y h:i A", strtotime($l_LoginDate_res));
    
    //display the last login date and time
    print('<div class="alert alert-info"><h5 style="text-align:right">logged in at ' .$l_LoginDate_res. '</h5></div>');
?>
   <br>
      <div class="row ng-cloak">
        <div class="container">
           <div ng-controller="dbCtrl">
          <input type="text" ng-model="searchFilter" class="form-control">
            <table class="ady-table-content" border="1" style="width:100%">
                <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Name</th>
                        <th>Emailid's</th>
                        <th>Project Duration</th>
                        <th>Relation</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
  <tbody > <p>Number of filtered items: {{data.length/2}}</p>
  <tr style="width:100%; border: 1px solid darkorange;" ng-repeat="value in data | filter:searchFilter"> 
  
      <td><a data-toggle="modal" data-target="#myModal" ng-click="showdetails(value.PR_id,value.TM_id,value.TM_Name);" ng-if="value.UR_Type=='G'" > {{value.TM_Name}}</a></td>
      <td >{{value.UR_FirstName}} </td>
      <td><span class="badgeinfo">Email: {{value.UR_Emailid}}@{{value.UR_EmailidDomain}}</span></td>
      <td>{{value.TM_PR_Duration}}</td>
      <td><span class="btn btn-default btn-xs" ng-if="value.UR_Type=='M'">Mentor</span><span  class="badgeinfo" ng-if="value.UR_Type=='G'">Guide | <a ng-href="blob_download.php?pdid={{value.PD_id}}"  >Last Document </a><span ng-if="value.PR_Status=='Accepted'" style="color:green" >{{value.PR_Status}}</span><span ng-if="value.PR_Status=='Pending'" style="color:orange" >{{value.PR_Status}}</span><span ng-if="value.PR_Status=='Rejected'" style="color:#0a86f1;" >{{value.PR_Status}}</span></td>
      <td><span  class="badgeinfo"  style="
    background-color: rgb(178, 239, 251);" ng-if="value.UR_Type=='G'">{{value.Expiry_date}}</span></td>
    </tr>        
  </tbody>
            </table>
            
            <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" ng-model="teamname">{{teamname}}Details </h4>
        </div>
        <div class="modal-body">
 <table class="ady-table-content" border="1" style="width:100%" >
 <thead>
                    <tr style="width:100%; border: 1px solid darkorange;">
                        <th >Project Name</th><td ng-repeat="m in members |limitTo:1">{{m.PR_Name}}</td> 
                    </tr>
                   <tr style="width:100%; border: 1px solid darkorange;">
                        <th>Team Members Name</th><td><span class="badge"  ng-repeat="m in members">{{m.UR_FirstName}}</span></td> 
                        
                   </tr>
                  
                </thead>

 </table><div id="summary"></div>
<hr>   
          <table class="ady-table-content" border="1" style="width:100%">
          <thead>
                    <tr style="width:100%; border: 1px solid darkorange;">
                        <th>Document Name</th>
                        <th>Submition Date</th>
                        <th>Guide Feedback</th>
                        <th>Guide Feedback Date</th>
                        <th>Guide Rating</th>
                        <th>Mentor Feedback </th>
                        <th>Mentor Date</th>
                        <th>Mentor Rating</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
<tbody >
    
          <tr style="width:100%; border: 1px solid darkorange;" ng-repeat="d in details">
             
       <td>{{d.AL_Desc}}</td>
             <td>{{d.PD_SubmissionDate}}</td>
             <td ><div class="show">{{d.PD_Feedback}}<div><span ng-if="!d.PD_Feedback">Pending</span></td>
             <td>{{d.PD_FeedbackDate}}<span ng-if="!d.PD_FeedbackDate">Pending</span></td>
              <td>{{d.PD_Rating}}<span ng-if="!d.PD_Rating">Pending</span></td>
               <td>{{d.PD_MFeedback}}<span ng-if="!d.PD_MFeedback">Pending</span></td>
                <td>{{d.PD_MFeedbackDate}}<span ng-if="!d.PD_MFeedbackDate">Pending</span></td>
                 <td>{{d.PD_MRating}}<span ng-if="!d.PD_MRating">Pending</span></td>
            <td ><span style="color: #3eaa27;" ng-if="d.PD_Status=='A'">Accepted</span><span style="color:orange" ng-if="d.PD_Status=='R'">Rejected</span><span ng-if="d.PD_Status=='P'">Pending</span></td>
       </tr>
          </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
            
            
          </div>
        </div>
      </div>

</div> 
<script>
  var fetch = angular.module('fetch', []);
        fetch.controller('dbCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.url1 = 'allteams.php';
      $scope.url2 = 'teamdocstatus.php';
      $scope.url3 = 'teammembers.php';
            $scope.url4='teamdocumentslist.php';

      $scope.getlist = function(dc) {
      
        $scope.teamname=c;
        $scope.prid=a;
        $scope.tmid=b;
            $http.post($scope.url2,{"tmid":$scope.tmid,"prid":$scope.prid}).
                        success(function(data,status) {
                     $scope.details=data;
   })
      };
      
      $scope.showdetails = function(a,b,c) {
       $scope.details="";
       $scope.members="";
        $scope.teamname=c;
        $scope.prid=a;
        $scope.tmid=b;
            $http.post($scope.url2,{"tmid":$scope.tmid,"prid":$scope.prid}).
                        success(function(data,status) {
                     $scope.details=data;
   });
      
      $http.post($scope.url3,{"tmid":$scope.tmid,"prid":$scope.prid}).
                        success(function(data,status) {
                  $scope.members=data;
                     });
                     
                     $http.post($scope.url4,{"tmid":$scope.tmid,"prid":$scope.prid}).
                        success(function(data,status) {
                  $('#summary').html(data);
                     });
      
      };
          
          $http.post($scope.url4,{"tmid":$scope.tmid,"prid":$scope.prid}).
                        success(function(data,status) {
                         //alert(data);
                  $('#summary').html(data);
                     });
                     
         $http.post($scope.url1)
                .success(function(data){
                 $scope.data = data;
                    console.log($scope.data);
                })
                .error(function() {
                    $scope.data = "error in fetching data";
                });
        }]);

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
    </script>
    <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
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
<?php include('footer.php')?>
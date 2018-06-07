<?php session_start();
$_SESSION['g_conid'];
 $_SESSION['g_convnodeid'];
$_SESSION['d_turn_count'];
$_SESSION['d_request_count'];
 //print_r($_SESSION);
 //session_destroy();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>IBM Chatbot</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
  <style>
  input.red::-webkit-input-placeholder {
   color: red;
}


.blue{
  color:blue;
}
  </style>
  <style>
.chip {
    display: inline-block;
    padding: 0 25px;
    height: 50px;
    font-size: 16px;
    line-height: 50px;
    border-radius: 25px;
    background-color: #f1f1f1;
}

.chip img {
    float: left;
    margin: 0 10px 0 -25px;
    height: 50px;
    width: 50px;
    border-radius: 50%;
}


</style>
</head>
<body>
 
<div class="container" ng-app="myApp" ng-controller="myCtrl">
 <div class="panel-group">
    <div class="panel panel-primary">
      <div class="panel-heading">Chat with us</div>
     <button ng-click="exit();">Exit</button>
 <form name="userForm">
       <div  style="height: 400px; max-height: 10;overflow-y: scroll;" class="panel-body">
         <div ng-repeat='x in lst'>
           <p><h4><span id="a" style="border-radius: 50%;">You : {{x.item.you}}</span></h4></p>
           <p> <h4 style=" text-align: right;
           padding: 3px;"><span>{{x.item.chatbot}} : Chatbot</span></h4></p>
          <h4 style=" text-align: right;
           padding: 3px;"><p ng-if="x.item.hreflink">For more information {{x.item.hreflink}}<a href='https://zaireprojects.com/dev/elearndev/book/mainbook/chat.php?get_msg={{x.item.hreflink}}'> Click me </a> :Chatbot</p></h4>
        </div>
      </div>
 <input id="div1" placeholder="Type and Hit enter..." ng-model="question" ng-keyup="$event.keyCode == 13 ? callconversation(userForm.$valid) : null" type="text" class="form-control"></input><br>
  
    </form>
    </div>
    
  </div>
  
</div>

</body>

<script src="app.js"></script>


</html>

var app = angular.module('myApp', []);

app.directive('schrollBottom', function () {
  return {
    scope: {
      schrollBottom: "="
    },
    link: function (scope, element) {
      scope.$watchCollection('schrollBottom', function (newValue) {
        if (newValue)
        {
          $(element).scrollTop($(element)[0].scrollHeight);
        }
      });
    }
  }
})
.controller('myCtrl', function($scope,$http,$location) {

$scope.url = 'chatbot.php';
$scope.lst = []    

  $scope.callconversation = function(isValid) {
  
//var c = '<?php echo $_SESSION['g_conid']; ?>';
//var b=  ' <?php echo $_SESSION['g_convnodeid']; ?>';
//alert('conv='+c+b);
   if (isValid && $scope.question!=null) {
              $http.post($scope.url, {"textdata":$scope.question}).
                        success(function(data, status) {
                          //console.log(data);
                            $scope.status = status;
                            $scope.data = data;
                            $scope.result = data; 
                           $scope.data=data;
                       // var arr =$scope.reply1;
                        
                        addItem=data;
                     // console.log(addItem);
                             
if(addItem){
$scope.lst.push({item: addItem});
$scope.question=null;
}     
                  })
            }else{
            document.getElementById("div1").className += "red";
          document.getElementById("div1").placeholder = "Type something here.....";
            }

        }
        
        $scope.exit = function($route) {
        $http.post("exitchat.php").
                        success(function(data, status) {
                      $scope.myUrl = $location.absUrl();
                      window.location.reload($scope.myUrl);
                 })
        }
        
});



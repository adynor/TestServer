<?php session_start();
// echo $_COOKIE["user_id"];
  //      echo $_COOKIE["user_psw"];
   //     echo "set";
         ?>
 <!DOCTYPE html>
<html>
<head>
<title>Facebook Login JavaScript Example</title>
<meta charset="UTF-8">
</head>
  <body>
        <div id="fb-root"></div>
<script>

      window.fbAsyncInit = function() {
          FB.init({appId: '1178930968862805', status: true, cookie: true, xfbml: true});

      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());

    function fetchUserDetail()
    {
        FB.api('/me', function(response) {
                alert("Name: "+ response.name + "\nFirst name: "+ response.first_name + "ID: "+response.id);
            });
            
            var yetVisited = localStorage['UR_Type'];
    if (!yetVisited) {
        // open popup
        localStorage['UR_Type'] = "S";
        localStorage['UR_id'] = "tests1";
    } 
        //window.location.href = 'http://zaireprojects.com/test/SHome.php';  
    }

    function checkFacebookLogin() 
    {
        FB.getLoginStatus(function(response) {
          if (response.status === 'connected') {
            fetchUserDetail();
          } 
          else 
          {
            initiateFBLogin();
          }
         });
    }

    function initiateFBLogin()
    {
        FB.login(function(response) {
           fetchUserDetail();
         });
    }
    </script>


 <input class="fb-login-button" type="button" value="Sign in using Facebook" onclick="checkFacebookLogin();"/>
<div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" ></div>

</body>
</html>

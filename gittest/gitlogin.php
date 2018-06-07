<?php
define('OAUTH2_CLIENT_ID', '570eb91aa88f713507b6');
define('OAUTH2_CLIENT_SECRET', '80fb9ec6e8804531dacfb16aebef43818bfd1c96');
$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';
session_start();
// Start the login process by sending the user to Github's authorization page
if(get('action') == 'login') {
  // Generate a random hash and store in the session for security
  $_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
  unset($_SESSION['access_token']);
  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
    'scope' => 'repo,user',
    'state' => $_SESSION['state']
  );
  // Redirect the user to Github's authorization page
  header('Location: ' . $authorizeURL . '?' . http_build_query($params));
  die();
}
// When Github redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {
  // Verify the state matches our stored state
  if(!get('state') || $_SESSION['state'] != get('state')) {
    //header('Location: ' . $_SERVER['PHP_SELF']);
    echo "<script>window.location.href='".$_SERVER['PHP_SELF']."'</script>";
    die();
  }
  // Exchange the auth code for a token
  //   'redirect_uri' => 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],

  $token = apiRequest($tokenURL, array(
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' => 'https://zaireprojects.com/test/gittest/gitpostreturn.php',
    'state' => $_SESSION['state'],
    'code' => get('code')
  ));
  //print_r($token);
   $_SESSION['access_token'] = $token->access_token;
  //header('Location: ' . $_SERVER['PHP_SELF']);
  echo "<script>window.location.href='".$_SERVER['PHP_SELF']."'</script>";
  die();

}
if(session('access_token')) {
  $user = apiRequest($apiURLBase . 'user');
  echo "<pre>";
  print_r($user);
  echo '<h3>Logged In</h3>';
  echo '<h4>Name:' . $user->login . '</h4>';
  $_SESSION['login'] = $user->login;
  $post_args = array(
    'name'=> 'testgit56'
      ) ;
  $dataen = json_encode($post_args);
  //$urlrepo='https://api.github.com/';
  //$urlrepo='https://developer.github.com/v3/user/repos';
 $repo= apiRequest($apiURLBase.'user/repos',$post_args);
  print_r($repo);
  echo '<a href="githome.php">Welcome to git Home</a>';
  
} else {
  echo '<h3>Not logged in</h3>';
  echo '<p><a href="?action=login">Log In</a></p>';
}


function apiRequest($url, $post=FALSE, $headers=array()) {
echo $url;
  $ch = curl_init($url);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  if($post){
  print_r($post);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
  $headers[] = 'Accept: application/json';
  if(session('access_token')){
  echo session('access_token');
    $headers[] = 'Authorization: token ' . session('access_token');
    }
  print_r( $headers);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_VERBOSE, true);

   $response = curl_exec($ch);
  return json_decode($response);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}
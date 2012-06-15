<?
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  
  include('includes/config.php');
  require_once('includes/facebook.php');
  $facebook = new Facebook(array(
  "appId"   => '253395578066052',
  "secret"  => '23d20951b5546544b2f2e31183e4b5c0',
  "cookie"  => false
  ));
  
  

  $user_id = $facebook->getUser();
?>
<html>
  <head></head>
  <body>

  <?
    if($user_id) {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
        $ret_obj = $facebook->api('/me/feed', 'POST',
                                    array(
                                     
                                      'message' => 'my debate',
                                      'message_tags' => 'the first debate',
                                      'name' => 'name is iitdebates',
                                      'description' => 'this is the way to describe things.',
                                      'story' => 'this is the story'
                                 ));
        echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';

      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl( array(
                       'scope' => 'publish_stream'
                       )); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
      // Give the user a logout link 
      echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
    } else {

      // No user, so print a link for the user to login
      // To post to a user's wall, we need publish_stream permission
      // We'll use the current URL as the redirect_uri, so we don't
      // need to specify it here.
      $login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream' ) );
      echo 'Please <a href="' . $login_url . '">login.</a>';

    } 

  ?>      

  </body> 
</html>
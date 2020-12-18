<?php
error_reporting(0);
session_start();
?>
<h2>How to post tweet on Twitter with PHP.</h2>
<?php
require_once('oauth/twitteroauth.php');
require_once('config.php');
if(isset($_POST["status"]))
{
    $status = $_POST["status"];
    if(strlen($status)>=130)
    {
            $status = substr($status,0,130);
    }
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    $connection->post('statuses/update', array('status' => "$status"));
    $message = "Tweeted Sucessfully!!";
}
if(isset($_GET["redirect"]))
{
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
    $request_token = $connection->getRequestToken(OAUTH_CALLBACK);

    $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
     
    switch ($connection->http_code) {
      case 200:
        $url = $connection->getAuthorizeURL($token);
        header('Location: ' . $url); 
        break;
      default:
        echo 'Could not connect to Twitter. Refresh the page or try again later.';
    }
   
}
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    
    echo '<a href="./index.php?redirect=true"><img src="./images/twitter.png" alt="Sign in with Twitter"/></a>';
}
else
{
    echo "<a href='logout.php'>Logout</a><br>";
    echo '<br>'.$message.'<br>
    <form action="index.php" method="post">
        <input type="text" name="status" id="status" placeholder="Write a comment...."><input type="submit" value="Post Tweet!" style="padding: 5px;">
    </form>';
}
?>
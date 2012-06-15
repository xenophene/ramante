<?php

include('includes/config.php');
require_once('includes/facebook.php');
$facebook = new Facebook(array(
"appId"   => '253395578066052',
"secret"  => '23d20951b5546544b2f2e31183e4b5c0',
"cookie"  => false
));

session_destroy()
?>

<html>
    <head>
        <body>
            Logout success !!
        </body>
    </head>
</html>
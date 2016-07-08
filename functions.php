<?php


include("pbkdf2.php");


if($prettyurls) {
    define('DEVICESURL',       $baseurl."/devices");
    define('RESETURL',         $baseurl."/reset");
    define('LOGINURL',         $baseurl."/login");
    define('REGISTERURL',      $baseurl."/create-account");
    define('PLUGINURL',        $baseurl."/synccit-apps");
    define('LOGOUTURL',        $baseurl."/logout/@s");
    define('FAQURL',           $baseurl."/faq");
    define('PROFILEURL',       $baseurl."/profile");
    define('DEVICESRMURL',     $baseurl."/remove/@k/@h");
    define('INDEXURL',         $baseurl."/");
    define('DONATEURL',        $baseurl."/donate");
    define('BASEURL',          $baseurl."/");
} else {
    define('DEVICESURL',       $baseurl."/addkey.php");
    define('RESETURL',         $baseurl."/reset.php");
    define('LOGINURL',         $baseurl."/login.php");
    define('REGISTERURL',      $baseurl."/create.php");
    define('PLUGINURL',        $baseurl."/plugin.php");
    define('LOGOUTURL',        $baseurl."/logout.php?l=@s");
    define('FAQURL',           $baseurl."/faq.php");
    define('PROFILEURL',       $baseurl."/profile.php");
    define('DEVICESRMURL',     $baseurl."/addkey.php?code=@k&amp;hash=@h&amp;do=remove");
    define('INDEXURL',         $baseurl."/");
    define('DONATEURL',        $baseurl."/donate.php");
    define('BASEURL',          $baseurl."/");
}


function genrand() {
    $rand = "";
    for($i=0; $i<6; $i++) {
        // this was posted on stack overflow
        $rand .= rand(0,1) ? rand(0,9) : chr(rand(ord('a'), ord('z')));
    }
    return $rand;
}


// themeing
function htmlHeader($title, $loggedin=false) {
	global $baseurl;
    if($loggedin) {
        global $session;
        $key = $session->hash;
    }
	?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- 1140px Grid styles for IE -->
    <!--[if lte IE 9]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" /><![endif]-->

    <!-- The 1140px Grid - http://cssgrid.net/ -->
    <link rel="stylesheet" href="css/1140.css" type="text/css" media="screen" />

    <!-- Your styles -->
    <link rel="stylesheet" href="css/styles.css" type="text/css" media="screen" />

    <!--css3-mediaqueries-js - http://code.google.com/p/css3-mediaqueries-js/ - Enables media queries in some unsupported browsers-->
    <script type="text/javascript" src="js/css3-mediaqueries.js"></script>

    <!--Title Font-->
    <link href='http://fonts.googleapis.com/css?family=Advent+Pro:100' rel='stylesheet' type='text/css'>

    <!-- body font-->
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>

    <!-- this is for the flattr button. no reason to leave it in if you aren't using it -->
    <script type="text/javascript">
        /* <![CDATA[ */
        (function() {
            var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
            s.type = 'text/javascript';
            s.async = true;
            s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
            t.parentNode.insertBefore(s, t);
        })();
        /* ]]> */
    </script>

</head>


<body>

<div class="container">
    <div class="row titlebar">
        <div class="tencol">
            <p class="title"><a href="<?php echo INDEXURL; ?>">synccit</a></p>
        </div>
        <div class="twocol last">
            <div class="donate"><a href="<?php echo DONATEURL; ?>">donate</a></div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row menubar">
        <?php
        if($loggedin) {
            ?>
        <div class="twocol menubaritem">
            <p><a href="<?php echo PLUGINURL; ?>">Get the apps</a></p>
        </div>
        <div class="twocol menubaritem">
            <p><a href="<?php echo DEVICESURL; ?>">Manage Devices</a></p>
        </div>
        <div class="twocol menubaritem">
            <p><a href="<?php echo PROFILEURL; ?>">Profile</a></p>
        </div>
        <div class="twocol menubaritem">
            <p><a href="<?php echo FAQURL; ?>">FAQ</a></p>
        </div>
        <div class="twocol menubaritem">
            <p><a href="https://twitter.com/synccit">Twitter</a></p>
        </div>
        <div class="twocol menubaritem last">
            <p><a href="<?php echo str_replace("@s", $key, LOGOUTURL); ?>">Logout</a></p>
        </div>
            <?php
        } else {
            ?>
            <div class="twocol menubaritem">
                <p><a href="<?php echo PLUGINURL; ?>">Get the apps</a></p>
            </div>
            <div class="twocol menubaritem">
                <p><a href="<?php echo FAQURL; ?>">FAQ</a></p>
            </div>
            <div class="twocol menubaritem">
                <p><a href="https://twitter.com/synccit">Twitter</a></p>
            </div>
            <div class="twocol menubaritem">
                <p></p>
            </div>
            <div class="twocol menubaritem">
                <p><a href="<?php echo LOGINURL; ?>">Login</a></p>
            </div>
            <div class="twocol menubaritem register last">
                <p><a href="<?php echo REGISTERURL; ?>">Register</a></p>
            </div>
            <?php
        }?>
</div>

<div class="container">
    <div class="row rowmain">

	<?php
}

function htmlFooter() {
	?>
	</div>

</div>

<div class="container">

    <div class="row lastrow">
        <div class="fourcol">
            <p class="footer footleft"><a href="http://twitter.com/synccit" target="_blank">@synccit</a> | <a href="mailto:james@drakeapps.com">james@drakeapps.com</a></p>
        </div>
        <div class="fourcol">
            <p class="cite"></p>
        </div>
        <div class="fourcol last">
            <p class="footer footright"><a href="http://drakeapps.com" target="_blank">Drake Apps</a> | <a href="https://github.com/drakeapps/synccit" target="_blank">Open Source</a></p>
        </div>
    </div>
</div>
</body>
</html><?php
}

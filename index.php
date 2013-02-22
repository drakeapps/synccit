<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");

//$loggedin = checkLoggedIn($_SESSION['auth'], $_SESSION['user'], $_SESSION['hash']);
$loggedin = $session->isLoggedIn();


if($loggedin) {
	//$user = getUserInfo($_SESSION['user']);
    $user = new User();
    $user->login($session->user);
	//$last10Links = getLinks($user, 10, 0, "desc");
	$title = "synccit - ".$user->username." history";
} else {
	$title = "synccit - reddit history sync";
}


htmlHeader($title, $loggedin);


if($loggedin) {
	?>
<div class="fourcol">
    <h2>Step 1</h2>
    <p class="indexloggedin">
        <a href="<?php echo DEVICESURL; ?>"><img src="images/add.png" alt="add" width="256" /></a>
        <br /><br />
        <a href="<?php echo DEVICESURL; ?>">Add new device or two</a>
    </p>
</div>
<div class="fourcol">
    <h2>Step 2</h2>
    <p class="indexloggedin">
        <a href="<?php echo PLUGINURL; ?>"><img src="images/download.png" alt="download" width="256" /></a>
        <br /><br />
        <a href="<?php echo PLUGINURL; ?>">Download the apps</a>
    </p>
</div>

<div class="fourcol last">
    <h2>Step 3</h2>
    <p class="indexloggedin">
        <img src="images/ok.png" alt="success" width="256" />
        <br /><br />
        Enter your username and an auth code in the app. Then just browse reddit!
    </p>
</div>

    <?php
    //echo "Welcome back, ".$user->username."<br /><br />stats probably. list of recently saved stuff";
} else {
    ?>
<div class="fourcol">

    <p class="indexloggedin">
        <img src="images/idevice.png" alt="idevice" />
        <br /><br />
        Track your reddit history on your phone
    </p>
</div>
<div class="fourcol">

    <p class="indexloggedin">
        <img src="images/laptop.png" alt="laptop" /></a>
        <br /><br />
        And sync it to your laptop
    </p>
</div>

<div class="fourcol last">

    <p class="indexloggedin">
        <img src="images/idevice.png" alt="idevice" /></a>
        <br /><br />
        And then back to your phone
    </p>
</div>

<div class="fourcol">

</div>
<div class="fourcol signupcolumn">
<a href="<?php echo REGISTERURL; ?>" class="signupbutton" >Sign Up Now</a>
</div>
<div class="fourcol last">

</div>
    <?php
	//echo "Take your reddit history to any device!<br /><br />Your visited links and comments are saved here, so when you browse reddit from another computer or device, your links are purple.";
}


htmlFooter();
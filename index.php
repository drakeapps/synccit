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
<div id="index">
    <span class="loggedin">Logged in as <?php echo $user->username; ?></span>
    <div class="cols">
        <span class="col1">
            <!--Stats! <br /> there aren't any yet. :(
            <br />
            <br />-->
            <br />
            <span class="step">Step 1.</span><br /><br />
            <a href="addkey.php" class="biglink">Add/edit Devices</a><br /><br /><br />
            <span class="step">Step 2.</span><br /><br />
            <a href="plugin.php" class="biglink">Download browser plugin</a><br /><br /><br />
            <span class="step">Step 3.</span><br /><br />
            For chrome: it's on the Chrome web store, shouldn't be an issue anymore<br /><br />
            For firefox: if it didn't automatically install, drag synccit.xpi onto firefox window to install


            <br /><br /><br />
            <span class="step">Step 4.</span><br /><br />
            Go to reddit.com, enter username and auth code from <a href="addkey.php">devices page</a>.<br /><br /><br />
            <span class="step">Step 5.</span><br /><br />
            Do the same for your other computers to sync all the things!
        </span>
        <!--<span class="col2">
            <a href="addkey.php" class="biglink">Add/edit Devices</a>
        </span>
        <span class="col3">
            <a href="plugin.php" class="biglink">Download browser plugin</a>
        </span>-->
    </div>


</div>

    <?php
    //echo "Welcome back, ".$user->username."<br /><br />stats probably. list of recently saved stuff";
} else {
    ?>
<div id="index">
    Take your reddit history to any device!

    <br /><br />
    Your visited links and comments are saved here, so when you browse reddit from another computer or device, your links are purple.

    <br /><br />
    To get started, <a href="create.php">sign up now</a>
</div>
    <?php
	//echo "Take your reddit history to any device!<br /><br />Your visited links and comments are saved here, so when you browse reddit from another computer or device, your links are purple.";
}


htmlFooter();
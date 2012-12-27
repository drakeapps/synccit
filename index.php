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
	echo "Welcome back, ".$user->username."<br /><br />stats probably. list of recently saved stuff";
} else {
	echo "Take your reddit history to any device!<br /><br />Your visited links and comments are saved here, so when you browse reddit from another computer or device, your links are purple.";
}


htmlFooter();
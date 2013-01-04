<?php

include("config.php");
include("functions.php");
include("session.php");

$loggedin = $session->isLoggedIn();

if($_GET['l'] == $session->hash) {

    $session->destroyPHPSession();
    header("Location: login.php");

} else {
    header("Location: index.php");
}

die;

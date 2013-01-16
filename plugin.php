<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");

$loggedin = $session->isLoggedIn();

htmlHeader("synccit - download browser plugin", $loggedin);


?>

<div id="center">

    <a href="https://chrome.google.com/webstore/detail/synccit-for-reddit/djgggkkgpoeknlpdllmhdagbfnhaigmd" class="biglink" target="_blank">Chrome Extension</a><br /><br />

    <a href="https://github.com/drakeapps/synccit-browser-extension/raw/master/synccit.xpi" class="biglink">Firefox Plugin</a>

    <br /><br /><br />

    <a href="https://github.com/drakeapps/synccit-browser-extension/raw/master/synccit.user.js" class="greylink">Greasemonkey User Script</a>


</div>

<?php

htmlFooter();
?>
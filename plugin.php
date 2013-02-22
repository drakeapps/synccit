<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");

$loggedin = $session->isLoggedIn();

htmlHeader("Get the synccit apps - synccit - reddit history/link sync", $loggedin);


?>

<div class="fourcol appdiv">
    <h2>Browsers</h2>

    <br />

    <a href="https://chrome.google.com/webstore/detail/synccit-for-reddit/djgggkkgpoeknlpdllmhdagbfnhaigmd" target="_blank"><img src="<?php echo BASEURL; ?>images/chrome.png" alt="chrome" /></a>
    <br />
    <span class="appname"><a href="https://chrome.google.com/webstore/detail/synccit-for-reddit/djgggkkgpoeknlpdllmhdagbfnhaigmd" target="_blank">Chrome</a></span>

    <br /><br />

    <a href="https://addons.mozilla.org/firefox/addon/synccit/" target="_blank"><img src="<?php echo BASEURL; ?>images/firefox.png" alt="firefox" /></a>
    <br />
    <span class="appname"><a href="https://addons.mozilla.org/firefox/addon/synccit/" target="_blank">Firefox</a></span>

    <br /><br />

    <a href="https://github.com/drakeapps/synccit-browser-extension/raw/master/synccit.user.js" target="_blank"><img src="<?php echo BASEURL; ?>images/greasemonkey.png" alt="greasemonkey" /></a>
    <br />
    <span class="appname"><a href="https://github.com/drakeapps/synccit-browser-extension/raw/master/synccit.user.js" target="_blank">Greasemonkey</a></span>

    <br /><br />

</div>
<div class="fourcol appdiv">
    <h2>Mobile</h2>

    <br />

    <a href="https://play.google.com/store/apps/details?id=com.andrewshu.android.redditdonation" target="_blank"><img src="<?php echo BASEURL; ?>images/reddit-is-fun-golden.png" alt="reddit is fun golden" /></a>
    <br />
    <span class="appname"><a href="https://play.google.com/store/apps/details?id=com.andrewshu.android.redditdonation" target="_blank">reddit is fun - golden platinum</a></span>

    <br /><br />

    <a href="https://play.google.com/store/apps/details?id=com.andrewshu.android.reddit" target="_blank"><img src="<?php echo BASEURL; ?>images/reddit-is-fun-golden.png" alt="reddit is fun" /></a>
    <br />
    <span class="appname"><a href="https://play.google.com/store/apps/details?id=com.andrewshu.android.reddit" target="_blank">reddit is fun</a></span>

    <br /><br />

</div>
<div class="fourcol appdiv last">
    <h2>Developers</h2>

    <br />

    <a href="https://github.com/drakeapps/synccit#api-docs" target="_blank"><img src="<?php echo BASEURL; ?>images/github.png" alt="github" /></a>
    <br />
    <span class="appname"><a href="https://github.com/drakeapps/synccit#api-docs" target="_blank">API Documentation</a></span>

    <br /><br />
    Looking to implement synccit in your own reddit app? Synccit has a completely open and well documented API. And the synccit.com server is ready for any traffic you send at it.

</div>
<?php

htmlFooter();
?>
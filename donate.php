<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");

$loggedin = $session->isLoggedIn();

htmlHeader("Donate - synccit - reddit history/link sync", $loggedin);


?>
<div class="fourcol">
    <div class="donatecontent">
        <h2>$10</h2>

        <br /><br />

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="GJZ5MH3PRWQUC">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>

        <br /><br />

        <form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/705368213954348" id="BB_BuyButtonForm10" method="post" name="BB_BuyButtonForm" target="_top">
            <input name="item_name_1" type="hidden" value="synccit"/>
            <input name="item_description_1" type="hidden" value="synccit donation"/>
            <input name="item_quantity_1" type="hidden" value="1"/>
            <input name="item_price_1" type="hidden" value="10.0"/>
            <input name="item_currency_1" type="hidden" value="USD"/>
            <input name="shopping-cart.items.item-1.digital-content.description" type="hidden" value="Thank you for your support!"/>
            <input name="shopping-cart.items.item-1.digital-content.url" type="hidden" value="http://synccit.com/thankyou"/>
            <input name="_charset_" type="hidden" value="utf-8"/>
            <input alt="" src="https://checkout.google.com/buttons/buy.gif?merchant_id=705368213954348&amp;w=117&amp;h=48&amp;style=white&amp;variant=text&amp;loc=en_US" type="image"/>
        </form>


    </div>
</div>
<div class="fourcol">
    <div class="donatecontent">
        <h2>$20</h2>

        <br /><br />


        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="FC2ZMWHH6P9A6">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>


        <br /><br />

        <form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/705368213954348" id="BB_BuyButtonForm20" method="post" name="BB_BuyButtonForm" target="_top">
            <input name="item_name_1" type="hidden" value="synccit"/>
            <input name="item_description_1" type="hidden" value="synccit donation"/>
            <input name="item_quantity_1" type="hidden" value="1"/>
            <input name="item_price_1" type="hidden" value="20.0"/>
            <input name="item_currency_1" type="hidden" value="USD"/>
            <input name="shopping-cart.items.item-1.digital-content.description" type="hidden" value="Thank you for your support!"/>
            <input name="shopping-cart.items.item-1.digital-content.url" type="hidden" value="http://synccit.com/thankyou"/>
            <input name="_charset_" type="hidden" value="utf-8"/>
            <input alt="" src="https://checkout.google.com/buttons/buy.gif?merchant_id=705368213954348&amp;w=117&amp;h=48&amp;style=white&amp;variant=text&amp;loc=en_US" type="image"/>
        </form>




    </div>
</div>
<div class="fourcol last">
    <div class="donatecontent">
        <h2>Any amount</h2>

        <br /><br />

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="N53CVYPYD49HN">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>


        <br /><br />

        <form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/705368213954348" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm" target="_top">
            <input name="item_name_1" type="hidden" value="synccit"/>
            <input name="item_description_1" type="hidden" value="synccit donation"/>
            <input name="item_quantity_1" type="hidden" value="1"/>
            <input name="item_price_1" type="text" value="" placeholder="enter amount" /><br />
            <input name="item_currency_1" type="hidden" value="USD"/>
            <input name="shopping-cart.items.item-1.digital-content.description" type="hidden" value="Thank you for your support!"/>
            <input name="shopping-cart.items.item-1.digital-content.url" type="hidden" value="http://synccit.com/thankyou"/>
            <input name="_charset_" type="hidden" value="utf-8"/>
            <input alt="" src="https://checkout.google.com/buttons/buy.gif?merchant_id=705368213954348&amp;w=117&amp;h=48&amp;style=white&amp;variant=text&amp;loc=en_US" type="image"/>
        </form>

        <br /><br />

        <a class="FlattrButton" style="display:none;" href="http://synccit.com/"></a>
        <noscript><a href="http://flattr.com/thing/1141267/synccit" target="_blank">
            <img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>

    </div>
</div>
<?php

htmlFooter();
?>
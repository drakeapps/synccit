<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");
include("email.php");

if(!isset($_SESSION['temphash'])) {
    $_SESSION['temphash'] = hash("sha256", genrand());
}

$error = "";

if($_POST['reset'] == "reset") {
    if($_POST['hash'] != $_SESSION['temphash']) {
        $error = "there was an error. retry";
    // check if it's a valid-ish email. it's not nothing. and it at least contains an @
    } else if(isset($_POST['email']) && $_POST['email'] != "" && count(explode("@", $_POST['email'])) == 2) {

        $sql = "SELECT * FROM user WHERE email = '".pg_escape_string($_POST['email'])."' LIMIT 1";

        $user = pg_query($sql);

        $user = pg_fetch_array($user, null, PGSQL_ASSOC);

        if($user) {

            if($user['email']) {

                $user_id = $user['id'];

                // sha1 of randomness
                $reset_hash = sha1($user_id.genrand().sha1($user['salt']).genrand().time().$user['username'].genrand());

                $sql = "
                    UPDATE user
                        SET
                          resethash = '".pg_escape_string($reset_hash)."',
                          canreset = '1'
                        WHERE
                            id = '".pg_escape_string($user_id)."'
                        LIMIT 1
                 ";

                pg_query($sql);



                $reset_url = $basehost.$baseurl."/"."reset.php?u=$user_id&t=".$reset_hash;

                send_email(
                    $user['email'],
                    "synccit password reset",
                    "You've requested a password reset for your synccit account.\r\n\r\n

                    To confirm your request, follow this link, $reset_url"
                );

                $error = "password reset email sent";
            } else { // considering it's searching via email this should never come up but whatever
                $error = "email not found";
            }


        } else {
            // security vulnerability?
            // you can search the db for emails
            // though you can do this with most things
            // a limit should probably be added
            $error = "email not found";
        }

    }
}


if(isset($_GET['u']) && ((int) $_GET['u'] > 0) && isset($_GET['t'])) {

    $u = (int) $_GET['u'];
    $sql = "SELECT * FROM user
            WHERE
                id = '".pg_escape_string($u)."'
                    AND
                resethash = '".pg_escape_string($_GET['t'])."'
                    AND
                canreset = '1'
            LIMIT 1";

    $user = pg_query($sql);
    $user = pg_fetch_array($user, null, PGSQL_ASSOC);

    if($user) {

        $hideform = 1;

        $user_id = $user['id'];


        $generated_password = genrand().genrand();

        $hashset = create_hash($generated_password);
        $pieces = explode(":", $hashset);
        $salt = $pieces[2];
        $hash = $pieces[3];

        $sql = "
                UPDATE user
                    SET
                      passhash = '".pg_escape_string($hash)."',
                      salt = '".pg_escape_string($salt)."',
                      canreset = '0'
                    WHERE
                        id = '".pg_escape_string($user_id)."'
                    LIMIT 1
            ";

        $reset = pg_query($sql);

        if($reset) {
            send_email(
                $user['email'],
                "synccit password reset",
                "your password has been reset to, ".$generated_password."\r\n\r\n

                try logging in with it"
            );

            $error = "new password has been emailed to you";
        } else {
            $error = "database error. sorry, try again";
        }



    } else {
        $error = "wrong reset code. try resetting again";
    }
}


$hash = $_SESSION['temphash'];

htmlHeader("synccit - reset password");

    ?>

<div class="fourcol">
    <h2>reset password</h2>
</div>
<div class="fourcol">
    <span class="error"><?php echo $error; ?></span><br /><br />
<form action="<?php echo RESETURL; ?>" method="post">

    <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
    <input type="hidden" name="reset" value="reset" />
    <?php if(!$hideform) { ?>
    <label for="email">email</label><br />
    <input type="text" id="email" name="email" value="" class="textcreate" />
    <br /><br />

    <input type="submit" value="reset" name="reset" class="submit" />

    <?php } ?>

</form>
</div>


<?php

htmlFooter();

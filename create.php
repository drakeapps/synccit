<?php

include("config.php");
include("functions.php");


if(isset($_POST['create'])) {

    $error = "";

    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if(count(explode("@", $email)) != 2 && !empty($email)) {
        $error = "email not valid"; // meh. emails aren't required so only check if @ exists
    }

    if(strlen($username) < 3) {
        $error = "username needs to be at least 3 characters long";
    }

    if(strlen($password) < 6) {
        $error = "password needs to be at least 6 characters long";
    }

    if(!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error = "username must consist of letters, numbers, or underscores";
    }

    if(strcmp($password, $_POST['passwordconfirm'])) {
        $error = "passwords do not match";
    }

    /*$hashset = create_hash($password);
        $pieces = explode(":", $hashset);
        $salt = $pieces[2];
        $hash = $pieces[3];*/

    //$error = pg_escape_string($username)." ".$email." ".$password." ".$salt." ".$hash;

    // no errors. make acct
    if($error == "") {

        $hashset = create_hash($password);
        $pieces = explode(":", $hashset);
        $salt = $pieces[2];
        $hash = $pieces[3];

        $sql = "INSERT INTO user (
            id,
            username,
            passhash,
            salt,
            email,
            created,
            lastip
        ) VALUES (
            NULL,
            '".pg_escape_string($username)."',
            '".pg_escape_string($hash)."',
            '".pg_escape_string($salt)."',
            '".pg_escape_string($email)."',
            '".time()."',
            '".pg_escape_string($_SERVER['REMOTE_ADDR'])."'
        )";

        if(pg_query($sql)) {
            //REDIRECT TO LOGIN
            header("Location: login.php");
            exit;
        } else {
            $r = pg_query("SELECT * FROM user WHERE username = '".pg_escape_string($username)."' LIMIT 1");
            if(pg_num_rows($r) > 0) {
                $error = "username already exists";
            } else {
                $error = "database error";
            }
        }
    }


}


htmlHeader("create account - synccit");

?>
<div class="fourcol">
    <h2>create new account</h2>
</div>
<div class="fourcol">
    <span class="error"><?php echo $error; ?></span><br /><br />
    <form action="<?php echo REGISTERURL; ?>" method="post">

        <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
        <label for="username">username</label><br />
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" class="textcreate" />
        <br /><br />
        <label for="password">password</label><br />
        <input type="password" id="password" name="password" value="" class="textcreate" />
        <br /><br />
        <label for="passwordconfirm">confirm password</label><br />
        <input type="password" id="passwordconfirm" name="passwordconfirm" value="" class="textcreate" />
        <br /><br />
        <label for="email">email</label><br />
        <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="textcreate" />
        <br /><br />

        <input type="submit" value="create" name="create" class="submit" />

    </form>
</div>
<div class="fourcol last">
    <p class="aside">
        <span class="bold">Privacy</span>:<br /><br />
        We won't reveal your username, password, or email to any third parties.
        We won't spam your email or send out any unsolicited emails without a quick and easy way to unsubscribe.
        Link information may be used for stats and other neat things, but the information will be kept anonymous.
        For added security, use a different username and password than your reddit account.
    </p>
</div>
<?php

htmlFooter();

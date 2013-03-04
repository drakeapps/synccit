<?php

include("config.php");
include("functions.php");
include("session.php");
include("userclass.php");

if(!isset($_SESSION['temphash'])) {
    $_SESSION['temphash'] = hash("sha256", genrand());
}

$loggedin = $session->isLoggedIn();

$hash = $_SESSION['temphash'];


if($loggedin) {
    //$user = getUserInfo($_SESSION['user']);
    $user = new User();
    $user->login($session->user);
    $title = "edit profile - synccit";
} else {
    header("Location: ".INDEXURL);
    exit;
}

if(isset($_POST['do']) && $_POST['do'] == "edit" && $_POST['hash'] == $hash) {
    $email = $_POST['email'];
    $oldpass = $_POST['oldpassword'];

    $hashset = "sha512:10000:".$user->salt.":".$user->passhash;

    $result = validate_password($oldpass, $hashset);

    if($result) {
        $addsql = "";


        if(isset($_POST['password']) && $_POST['password'] != "" && $_POST['password'] == $_POST['passwordconfirm']) {
            $hashset = create_hash($_POST['password']);
            $pieces = explode(":", $hashset);
            $salt = $pieces[2];
            $hash = $pieces[3];

            $addsql = " ,
                `passhash` = '".$mysql->real_escape_string($hash)."',
                `salt` = '".$mysql->real_escape_string($salt)."'
                ";
        }

        $sql = "
            UPDATE
                `user`
                SET
                  `email` = '".$mysql->real_escape_string($email)."'

                  $addsql

                WHERE
                    `id` = '".$user->id."'

                LIMIT 1
                  ";

        echo $sql;
        if($mysql->query($sql)) {
            $error = "updated successfully";
        } else {
            $error = "database error. try again";
        }

    } else {
        $error = "incorrect password";
    }


}
$links = $mysql->query("SELECT count(*) as `count` FROM `links` WHERE `userid` = '".$mysql->real_escape_string($user->id)."'");
$links = $links->fetch_assoc();
$links = $links['count'];

$devices = $mysql->query("SELECT count(*) as `count` FROM `authcodes` where `userid` = '".$mysql->real_escape_string($user->id)."'");
$devices = $devices->fetch_assoc();
$devices = $devices['count'];

htmlHeader("edit your profile - synccit - reddit history/link sync", $loggedin);


$_SESSION['temphash'] = hash("sha256", genrand());

?>

<div class="fourcol">
        <p><h2>edit profile</h2></p>
</div>
<div class="fourcol">
    <div class="">
        <span class="error"><?php echo $error; ?></span><br /><br />
        <form action="<?php echo PROFILEURL; ?>" method="post" id="editprofile">

            <input type="hidden" name="hash" value="<?php echo $_SESSION['temphash']; ?>" />
            <input type="hidden" name="do" value="edit" />
            username<br />
            <p class="right bold"><?php echo $user->username; ?></p>

            <label for="email">email</label><br />
            <input type="text" id="email" name="email" value="<?php echo $user->email; ?>" class="textcreate" />
            <br /><br />
            <br /><br />
            <p class="right">change password</p>
            <br />
            <label for="oldpassword">old password</label><br />
            <input type="password" id="oldpassword" name="oldpassword" value="" class="textcreate" />
            <br /><br />
            <label for="password">new password</label><br />
            <input type="password" id="password" name="password" value="" class="textcreate" />
            <br /><br />
            <label for="passwordconfirm">confirm password</label><br />
            <input type="password" id="passwordconfirm" name="passwordconfirm" value="" class="textcreate" />
            <br /><br />

            <input type="submit" value="save" name="save" class="submit" />

        </form>
    </div>
</div>
<div class="fourcol last">
    <div class="right">
        <h3>statistics</h3>
        <br /><br />
        registered <?php echo strtolower(date("F n, Y", $user->created)); ?>

<br /><br />
        <?php echo $links; ?> links viewed

<br /><br />
        <?php echo $devices; ?> devices

    </div>
</div>

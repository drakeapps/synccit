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
    $title = "synccit - add device key";
} else {
    header("Location: index.php");
    exit;
}

if(isset($_POST['submit']) and strcmp($hash, $_POST['hash']) == 0) {

    if(isset($_POST['device']) ) {
        $key = genrand();

        $sql = "INSERT INTO `authcodes` (
            `id`,
            `userid`,
            `username`,
            `authhash`,
            `description`,
            `created`
        ) VALUES (
            NULL,
            '".$mysql->real_escape_string($user->id)."',
            '".$mysql->real_escape_string($user->username)."',
            '".$key."',
            '".$mysql->real_escape_string($_POST['device'])."',
            '".time()."'
        )";
        if($res = $mysql->query($sql)) {
            $error = "device key added";
        } else {
            $error = "database error";
        }
    }

    $_SESSION['temphash'] = hash("sha256", genrand());
    $hash = $_SESSION['temphash'];


}

$sql = "SELECT * FROM `authcodes` WHERE `userid` = '".$mysql->real_escape_string($user->id)."' ORDER BY `created` DESC";
$res = $mysql->query($sql);
// this could be a separate class, but I'm pretty sure this is the only time it'll be used
$user = array();
$i=0;
while($row = $res->fetch_assoc()) {
    $user[$i++] = array(
        "id" => $row['id'],
        "description" => $row['description'],
        "code" => $row['authhash']
    );
}


htmlHeader($title, $loggedin);


?>
<div id="center">
    <span class="error"><?php echo $error; ?></span>
<form action="addkey.php" method="post">
    <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
    <label for="device">add new device</label><br />
    <input type="text" id="device" name="device" value="device name" class="text" onblur="if (this.value == '') {this.value = 'device name';}"
           onfocus="if (this.value == 'device name') {this.value = '';}" />
    <br />
    <input type="submit" name="submit" value="add device" />
</form>
    <br />
    <div id="codelist">
    <?php
        for($i=0;$i<count($user);$i++) {
            echo "<span class=\"leftside\">";
            echo $user[$i]['description'];
            echo "</span> <span class=\"rightside\">";
            echo $user[$i]['code'];
            echo "</span>";
            echo "<br />";
        }
    ?>
    </div>
    <div id="apiloc">
        <br />API Location<br />
        <span class="apiurl"><?php echo $apiloc; ?></span>
    </div>
</div>
<?php



htmlFooter();
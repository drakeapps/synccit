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
    $title = "device manager - synccit";
} else {
    header("Location: index.php");
    exit;
}

if(isset($_REQUEST['do']) && isset($_REQUEST['code']) && $_REQUEST['do'] == "remove") {

    $code = $_REQUEST['code'];


    if(strcmp($hash, $_GET['hash']) == 0) {

        $sql = "DELETE FROM `authcodes`
            WHERE
                `userid`    = '".$mysql->real_escape_string($user->id)."'
                    AND
                `username`  = '".$mysql->real_escape_string($user->username)."'
                    AND
                `authhash`  = '".$mysql->real_escape_string($code)."'
            LIMIT 1
        ;";


        if($res = $mysql->query($sql)) {
            $error = "device key removed";
        } else {
            //$error = "unable to remove key";
        }
    }

    $_SESSION['temphash'] = hash("sha256", genrand());
    $hash = $_SESSION['temphash'];
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
$codes = array();
$i=0;
while($row = $res->fetch_assoc()) {
    $codes[$i++] = array(
        "id" => $row['id'],
        "description" => $row['description'],
        "code" => $row['authhash']
    );
}


htmlHeader($title, $loggedin);


?>

<div class="threecol">
    <h2>device manager</h2>
    <p class="deviceinfo">

        <br />
				<span class="bold">
					username
				</span>
        <br />
				<span class="mono">
					<?php echo htmlspecialchars($user->username); ?>
				</span>
        <br />
    </p>
    <p class="deviceinfo">
        <br />
				<span class="bold">
					API location
				</span>
        <br />
				<span class="monosmall">
					<?php echo $apiloc; ?>
				</span>
        <br />
    </p>
</div>
<div class="sixcol">
    <div class="devicetable">
        <table>
            <thead>
            <tr>
                <td>device name</td>
                <td>auth code</td>
                <td>rm</td>
            </tr>
            </thead>
            <tbody>

            <?php
            for($i=0;$i<count($codes);$i++) {

                $url = str_replace("@k", $codes[$i]['code'], DEVICESRMURL);
                $url = str_replace("@h", $hash, $url);

                echo "<tr>
                <td>";
                echo $codes[$i]['description'];

                echo "</td> ";



                echo "<td class=\"authcode\">";
                echo $codes[$i]['code'];
                echo "</td>";


                echo "<td class=\"delete\">";
                echo "<a href=\"$url\"
                title=\"remove device key\"
                onClick=\"return confirm('Are you sure you want to delete the key? Anything using this key will stop working')\">";


                echo "[x]</a></td>";

                echo "</tr>";
            }
            ?>


            </tbody>
        </table>
    </div>

</div>
<div class="threecol last">
    <div class="adddevice">
        <br />
        <span class="adddevicetitle">add device</span>
        <span class="error"><?php echo $error; ?></span>
        <form action="<?php echo DEVICESURL; ?>" method="post">
            <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
            <input type="text" id="device" name="device" value="" class="text" placeholder="device name" />
            <br />
            <input type="submit" name="submit" value="add device"/>
        </form>
    </div>
</div>

<?php



htmlFooter();
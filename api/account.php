<?php

// This is completely based on api.php
// various changes here and there for the different functions


// depends on where api folder is located
// depending on where, might copy them to this folder
include("../config.php");
include("../functions.php");
include("../linkclass.php");
include("../session.php"); // account needs session.php. possibly only for hash generation

// these are separate from api.php versioning
$apiversion = 1; // current version of API. this will only deal with major changes
$apirevision = 1; // current revision. increments more. for smaller changes
header("X-API: $apiversion");
header("X-Revision: $apirevision");


if(isset($_POST['data'])) {

    // because I feel like people won't always do type=xml
    // if the data starts with <?xml, it definitely isn't JSON so just make it XML
    if(strpos($_POST['data'], "<?xml") === 0) {
        $_REQUEST['type'] = "xml";
    }

    if(strtolower($_REQUEST['type']) == "xml") {
        // afaik SimpleXML loves throwing actual errors instead of suppressing pretty much everything
        // put it in try catch to try to get rid of that
        try {

            // suppress simplexml warnings
            // if you're having issues with xml formatting, remove the @
            // also printing Exception $e in the catch will help
            @$xml = new SimpleXMLElement($_POST["data"]);
            $username   = $xml -> username;
            $auth       = $xml -> auth;
            $mode       = $xml -> mode;
            $developer  = isset($xml -> dev) ? $xml -> dev : "unknown";

            if($mode == "create") {
                $password   = $xml -> password;
                $email      = $xml -> email;

                $r = createAccount($username, $password, $email, $developer);

                if($r == "") {
                    xsuccess("account created", "xml");
                } else {
                    xerror($r, "xml");
                }


            } else if($mode == "addauth") {
                $password   = $xml -> password;
                $device     = $xml -> device;

                $r = addAuth($username, $password, $device, $developer);

                if($r["success"] == "" ) {
                    xerror($r["error"], "xml");
                } else {
                    xdevice($device, $r["success"], "xml");
                }
            }


            $authinfo = checkAuth($username, $auth, "xml");

            if($mode == "update") {
                $updates = array();
                foreach($xml -> links -> link as $link) {
                    // This part was a little tricky
                    // While $link->id seems like it might be a string, it's actually a SimpleXMLElement (should've print_r from the beginning)
                    // You can't use an object as an array index
                    // Putting "". in front of it makes $id a string
                    // Simple when you think about it. Annoying when it just gives you some weird error
                    $id = "".$link -> id;

                    if(isset($link -> comments) && $link -> comments > -1) {
                        $updates[$id]["comment"] = $link -> comments;
                        if($link -> both == true || $link -> both == "true" || $link -> both == 1) {
                            $updates[$id]["link"] = 1;
                        }
                    } else {
                        $updates[$id]["link"] = 1;
                    }
                }

                insertLinks($updates, $developer, $authinfo["userid"], $authinfo["device"]);

                xsuccess(count($updates)." links updated", "xml");
            } else {
                $links = array();
                $i = 0;
                foreach($xml -> links -> link as $link) {
                    $links[$i++] = "".$link -> id; // convert simplexml to string. not that important here though
                }
                $result = readLinks($links, $authinfo["userid"], "xml");
                echo $result;
                die;

            }


        } catch (Exception $e){
            xerror("xml error", "xml");
        }
        xerror("xml error");
    } else { //we're just going to assume json if data variable is set with no type

        // still sent on post var data
        $json = json_decode($_POST['data'], true);
        if($json == false || $json == null) {
            xerror("json error ".json_last_error(), "json");
        }

        $username   = $json["username"]; // still requires username
        $login      = $json["login"]; // no more auth, now login token
        $mode       = $json["mode"]; // still requres mode
        $developer  = isset($json["dev"]) ? $json["dev"] : "unknown";
        // dev auth will probably be required for account management

        // yay, the same
        if($mode == "create") {
            $password   = $json["password"];
            $email      = $json["email"];

            $r = createAccount($username, $password, $email, $developer);

            if($r == "") {
                xsuccess("account created", "json");
            } else {
                xerror($r, "json");
            }
        } else if($mode == "login") {
            // this will be really close to addauth
            // basically want loginhash, not authcode
            // login hash will be longer than auth hash. users aren't having to enter it
            $password = $json["password"];
            $r = addLogin($username, $password, $developer);

            if($r["success"] == "" ) {
                xerror($r["error"], "json");
            } else {
                xlogin($r["success"], "json");
            }


        // this will probably be the same
        }

        $authinfo = checkAuth($username, $login, "json");

        if($mode == "delete") {
            $updates = array();
            foreach($json["devices"] as $link) {
                $updates[$i++] = $link["id"];

            }

            //insertLinks($updates, $developer, $authinfo["userid"], $authinfo["device"]);

            deleteAuth($updates, $authinfo["userid"], $username);


            xsuccess(count($updates)." links updated", "json");

        } else if ($mode == "history") {

            $result = readHistory($authinfo["userid"], "json");
            echo $result;
            die;
        } else if ($mode == "devices") {
            $result = getDevices($authinfo["userid"], "json");
            echo $result;
            die;
        } else if($mode == "addauth") {

            $device = $json["device"];

            $r = addAuth($username, $authinfo["userid"], $device, $developer);

            if($r["success"] == "" ) {
                xerror($r["error"], "json");
            } else {
                xdevice($device, $r["success"], "json");
            }
        }

    }
// remove plain text. no plain text api for account
} else {
    xerror("no post data");
}


function checkAuth($username, $auth, $mode=false) {
    global $mysql;
    // seems running this and seeing if affected_rows was > 0 doesn't work.
    // this does help me get the user id I use later
    // but just using username probably wouldn't be a bad idea
    /*$sql = "UPDATE `authcodes`
        SET `lastused` = '".time()."'
        WHERE
            `username` = '".$mysql->real_escape_string($username)."' AND
            `authhash` = '".$mysql->real_escape_string($auth)."' LIMIT 1";*/


    // ok. seems that i only used userid in login codes
    // so probably should change that
    // but let's say i don't
    // so i can do a join
    // or 2 queries
    // i guess ill start with a join
    // we'll see if that's too slow/bad

    $sql = "SELECT `user`.`id` as userid FROM `logincodes`, `user`
        WHERE
            `user`.`username` = '".$mysql->real_escape_string($username)."'
                AND
            `logincodes`.`authhash` = '".$mysql->real_escape_string($auth)."'
                AND
            `user`.`id` = `logincodes`.`userid`

        LIMIT 1";



    if($res = $mysql->query($sql)) {
        //var_dump($result);
        if($res->num_rows > 0) {
            $info = $res->fetch_assoc();
            return array(
                'username'  => $username,
                'userid'    => $info['userid'],
                'login'     => $auth
            );
        } else {
            xerror("not authorized", $mode);
        }

    } else {
        xerror("not authorized", $mode);
    }
}

// SQL everywhere. it's not pretty
// basic logic is try to insert
// if fails, then update row
function insertLinks($updates, $developer, $user, $devicename) {
    global $mysql;
    //var_dump($updates);
    // just realized foreach can do keys. should change it
    while($current = current($updates)) {
        $linkid = key($updates);
        if(strlen($linkid) == 6) { // seems a blank link can get added and causes some trouble

            //$commentcount = $current['comment'] != NULL ? $current['commment'] : "-1";
            if($current['comment'] != NULL) {
                $commentcount = $current['comment'];
                $commenttime = time();
            } else {
                $commentcount = "-1";
                $commenttime = 0;
            }
            $linktime = $current['link'] == 1 ? time() : 0;


            $sql = "
                INSERT INTO `links`
                (
                  `id`,
                  `linkid`,
                  `userid`,
                  `lastvisit`,
                  `lastcommenttime`,
                  `lastcommentcount`,
                  `firstvisit`,
                  `lastcall`,
                  `developers`
                ) VALUES (
                  NULL,
                  '".$mysql->real_escape_string($linkid)."',
                  '".$mysql->real_escape_string($user)."',
                  '".$mysql->real_escape_string($linktime)."',
                  '".$mysql->real_escape_string($commenttime)."',
                  '".$mysql->real_escape_string($commentcount)."',
                  '".$mysql->real_escape_string($linktime)."',
                  '".$mysql->real_escape_string($devicename)."',
                  '".$mysql->real_escape_string($developer)."'
                )";
            $res = $mysql->query($sql);
            if(!$res) {

                $sql = "
                UPDATE `links`
                    SET
                ";
                if($commentcount != "-1") {
                    $sql .= "
                    `lastcommentcount`  = IF(`lastcommentcount` > '".$mysql->real_escape_string($commentcount)."', `lastcommentcount`, '".$mysql->real_escape_string($commentcount)."'),
                    `lastcommenttime`   = '".$mysql->real_escape_string($commenttime)."',
                    ";
                }
                if($linktime != 0) {
                    $sql .= "
                    `lastvisit` = '".$mysql->real_escape_string($linktime)."',
                    `firstvisit` = IF (`firstvisit` = 0, '".$mysql->real_escape_string($linktime)."', `firstvisit`),
                    ";
                }
                $sql .= "
                    `lastcall` = '".$mysql->real_escape_string($devicename)."',
                    `developers` = IFNULL(CONCAT(`developers`, ', ".$mysql->real_escape_string($developer)."'), '".$mysql->real_escape_string($developer)."')

                    WHERE
                        `linkid` = '".$mysql->real_escape_string($linkid)."'
                    AND
                        `userid` = '".$mysql->real_escape_string($user)."'

                    LIMIT 1
                ";
                $res = $mysql->query($sql);
                //var_dump($res);
            }
            //var_dump($res);
        }
        next($updates);
    }
}

function deleteAuth($updates, $userid, $username) {

    global $mysql;

    foreach($updates as $update) {

        $sql = "DELETE FROM `authcodes`
            WHERE
                `userid` = '".$mysql->real_escape_string($userid)."'
                  AND
                `username` = '".$mysql->real_escape_string($username)."'
                  AND
                `authhash` = '".$mysql->real_escape_string($update)."'

            LIMIT 1";


        $mysql->query($sql);

        // meh, not going to worry about success/failure

    }



}


// $type is output mode
// text, xml, json
// default (null) is text
function readHistory($user, $type=null) {
    global $mysql;

    // this is what i would want to do, but doesn't work
    $sql = "
        SELECT *
        FROM (
                SELECT `authhash` as `info`, `created` as `time`
                  FROM `authcodes`
                WHERE
                  `userid` = '3'
            UNION
              SELECT `linkid` as `info`, `lastvisit` as `time` FROM `links`
              WHERE
                 `links`.`userid` = '3'
            UNION
              SELECT `authhash` as `info`, `created` as `time` FROM `logincodes`
              WHERE
                `userid` = '3'
        ) results
        ORDER BY `time` DESC
    ";

    $sql = "SELECT * FROM `links` WHERE `userid` = '".$mysql->real_escape_string($user)."' ORDER BY `lastvisit` DESC LIMIT 20";



    //echo $sql;


    $result = $mysql->query($sql);

    if($result->num_rows < 1) {
        // this probably actually shouldn't be an error
        if($type == "json") {
            return "[]";
        } else if($type == "xml") {
            return '<?xml version="1.0"?>'."\n".'<synccit><links></links></synccit>';
        } else {
            xerror("no links found");
            return false;
        }

    }

    $output = "";

    if(is_null($type)) {

        while($link = $result->fetch_assoc()) {

            $output .= $link['linkid'].":".$link['lastvisit'].";".$link['lastcommentcount'].":".$link['lastcommenttime'].",\n";

        }

    } else if($type=="json") {
        $json = array();
        $i = 0;
        while($link = $result->fetch_assoc()) {
            $json[$i++] = array(
                "id"            => $link['linkid'],
                "lastvisit"     => $link['lastvisit'],
                "comments"      => $link['lastcommentcount'],
                "commentvisit"  => $link['lastcommenttime'],
                "type"          => "link"
            );
        }
        $output = json_encode($json);

    } else if($type="xml") {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><synccit></synccit>');
        $links = $xml->addChild("links");
        while($link = $result->fetch_assoc()) {
            $l = $links->addChild("link");
            $l->addChild("id", $link['linkid']);
            $l->addChild("lastvisit", $link["lastvisit"]);
            $l->addChild("comments", $link["lastcommentcount"]);
            $l->addChild("commentvisit", $link["lastcommenttime"]);
        }
        $output = $xml->asXML();
    }

    return $output;
}

function getDevices($user, $type=null) {
    global $mysql;

    // this is what i would want to do, but doesn't work

    $sql = "SELECT * FROM `authcodes` WHERE `userid` = '".$mysql->real_escape_string($user)."' ORDER BY `created` DESC";



    //echo $sql;


    $result = $mysql->query($sql);

    if($result->num_rows < 1) {
        // this probably actually shouldn't be an error
        if($type == "json") {
            return "[]";
        } else if($type == "xml") {
            return '<?xml version="1.0"?>'."\n".'<synccit><devices></devices></synccit>';
        } else {
            xerror("no links found");
            return false;
        }

    }

    $output = "";

    if(is_null($type)) {

        while($link = $result->fetch_assoc()) {

            $output .= $link['linkid'].":".$link['lastvisit'].";".$link['lastcommentcount'].":".$link['lastcommenttime'].",\n";

        }

    } else if($type=="json") {
        $json = array();
        $i = 0;
        while($link = $result->fetch_assoc()) {
            $json[$i++] = array(
                "auth"          => $link['authhash'],
                "device"        => $link['description'],
                "created"       => $link['created'],
                "createdby"     => ($link['createdby'] == null) ? "synccit.com" : $link['createdby']
            );
        }
        $output = json_encode($json);

    } else if($type="xml") {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><synccit></synccit>');
        $links = $xml->addChild("links");
        while($link = $result->fetch_assoc()) {
            $l = $links->addChild("link");
            $l->addChild("id", $link['linkid']);
            $l->addChild("lastvisit", $link["lastvisit"]);
            $l->addChild("comments", $link["lastcommentcount"]);
            $l->addChild("commentvisit", $link["lastcommenttime"]);
        }
        $output = $xml->asXML();
    }

    return $output;
}


function createAccount($username, $password, $email, $developer) {
    // This is copy and pasted from create.php
    // Will make account creation a separate class in the future
    $error = "";

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

    if($error == "") {

        global $mysql;

        $hashset = create_hash($password);
        $pieces = explode(":", $hashset);
        $salt = $pieces[2];
        $hash = $pieces[3];

        $sql = "INSERT INTO `user` (
            `id`,
            `username`,
            `passhash`,
            `salt`,
            `email`,
            `created`,
            `lastip`,
            `createdby`
        ) VALUES (
            NULL,
            '".$mysql->real_escape_string($username)."',
            '".$mysql->real_escape_string($hash)."',
            '".$mysql->real_escape_string($salt)."',
            '".$mysql->real_escape_string($email)."',
            '".time()."',
            '".$mysql->real_escape_string($_SERVER['REMOTE_ADDR'])."',
            '".$mysql->real_escape_string($developer)."'
        )";

        if($mysql->query($sql)) {
            // Success
            // just return nothing meaning no error
            $error = "";

        } else {
            $r = $mysql->query("SELECT * FROM `user` WHERE `username` = '".mysql_real_escape_string($username)."' LIMIT 1");
            if($r->num_rows > 0) {
                $error = "username already exists";
            } else {
                $error = "database error";
            }
        }
    }

    return $error;

}

function checkLogin($username, $password) {
    global $mysql;


    $userinfo = $mysql->query("SELECT * FROM `user` WHERE `username` = '".$mysql->real_escape_string($username)."' LIMIT 1");


    if($userinfo->num_rows > 0) {

        $user = $userinfo->fetch_assoc();

        $hash = $user["passhash"];
        $salt = $user["salt"];

        $hashset = "sha512:10000:".$salt.":".$hash;

        $result = validate_password($password, $hashset);

        if($result) {
            return array(
                'username'  => $user['username'],
                'userid'    => $user['id'],
                'device'    => "none"
            );
        } else {
            return false;
        }
    }
}

function addAuth($username, $userid, $device, $developer) {

    global $mysql;

    $success = "";
    $error = "";

    $key = genrand();



    $sql = "INSERT INTO `authcodes` (
        `id`,
        `userid`,
        `username`,
        `authhash`,
        `description`,
        `created`,
        `createdby`
    ) VALUES (
        NULL,
        '".$mysql->real_escape_string($userid)."',
        '".$mysql->real_escape_string($username)."',
        '".$key."',
        '".$mysql->real_escape_string($device)."',
        '".time()."',
        '".$mysql->real_escape_string($developer)."'
    )";
    if($res = $mysql->query($sql)) {
        $success = $key;
    } else {
        $error = "database error";
    }



    return array("success" => $success, "error" => $error);

}

function addLogin($username, $password, $developer) {

    global $mysql;

    $success = "";
    $error = "";

    //$key = genrand();

    $userinfo = $mysql->query("SELECT * FROM `user` WHERE `username` = '".$mysql->real_escape_string($username)."' LIMIT 1");


    if($userinfo->num_rows > 0) {

        $user = $userinfo->fetch_assoc();

        $hash = $user["passhash"];
        $salt = $user["salt"];

        $hashset = "sha512:10000:".$salt.":".$hash;

        $result = validate_password($password, $hashset);

        global $session;
        $session->generateHash();
        $loginhash = $session->hash;

        if($result) {
            $sql = "INSERT INTO `logincodes` (
                `id`,
                `userid`,
                `authhash`,
                `lastlogin`,
                `created`
            ) VALUES (
                NULL,
                '".$mysql->real_escape_string($user["id"])."',
                '".$mysql->real_escape_string($loginhash)."',
                '".time()."',
                '".time()."'
            )";
            if($res = $mysql->query($sql)) {
                $success = $loginhash;
            } else {
                $error = "database error";
            }
        } else {
            $error = "username or password incorrect";
        }




    } else {
        $error = "user not found";
    }

    return array("success" => $success, "error" => $error);

}


// simple outputs
// xerror for errors
// xsuccess for success
// xdevice only for adding of device auths
// xlogin only for creating login hash
// $mode for output type
// can be json or xml

function xerror($string, $mode=false) {
    header("X-Error: $string");
    if($mode == "json") {
        $json = array();
        $json["error"] = $string;
        echo json_encode($json);
    } else if($mode == "xml") {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><synccit></synccit>');
        $xml->addChild("error", $string);
        echo $xml->asXML();
    } else {
        echo "error: $string";
    }
    exit;
}

function xsuccess($string, $mode=false) {
    header("X-Success: $string");
    if($mode == "json") {
        $json = array();
        $json["success"] = $string;
        echo json_encode($json);
    } else if($mode == "xml") {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><synccit></synccit>');
        $xml->addChild("success", $string);
        echo $xml->asXML();
    } else {
        echo "success: $string";
    }
    exit;
}

function xdevice($device, $auth, $mode=false) {
    header("X-Success: device key added");
    if($mode == "json") {
        $json = array();
        $json["device"]     = $device;
        $json["auth"]       = $auth;
        $json["success"]    = "device key added";
        echo json_encode($json);
    } else if($mode == "xml") {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><synccit></synccit>');
        $xml->addChild("success", "device key added");
        $xml->addChild("device", $device);
        $xml->addChild("auth", $auth);
        echo $xml->asXML();
    } else {
        echo "auth: $auth";
    }
    exit;
}

function xlogin($login, $mode=false) {
    header("X-Success: login hash added");
    if($mode == "json") {
        $json = array();
        $json["login"]     = $login;
        $json["success"]    = "login hash added";
        echo json_encode($json);
    } else if($mode == "xml") {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><synccit></synccit>');
        $xml->addChild("success", "login hash added");
        $xml->addChild("login", $login);
        echo $xml->asXML();
    }
    exit;
}
<?php

// This will test all the functionality of the API
// It will create multiple user accounts and auth codes
// Also add random links associated with those created users

// API location
$url = "http://localhost/rsync/api/api.php";


function genrand() {
    $rand = "";
    for($i=0; $i<6; $i++) {
        // this was posted on stack overflow
        $rand .= rand(0,1) ? rand(0,9) : chr(rand(ord('a'), ord('z')));
    }
    return $rand;
}


function sendRequest($data) {
    global $url;
    $options = array('http' => array('method'  => 'POST','content' => http_build_query($data)));
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

$success = 0;
$error = 0;

/////////////////////////////
////    JSON              ///
/////////////////////////////

echo "<h2>JSON</h2>";

$username = "testuser_".genrand();
$password = "password"; // could be random I guess


// CREATE

echo "<h3>creating accounts</h3>";

echo "creating user $username";
echo "<br />";

$json = array(
    'username'  => $username,
    'password'  => $password,
    'dev'       => "API test script",
    'mode'      => "create"
);

$res = json_decode(sendRequest(array('data' => json_encode($json))), true);

if(isset($res['success'])) {
    echo "account created";
    $success++;
} else {
    echo "<h2>ERROR: ".json_decode($res, true)."</h2>";
    $error++;
}
echo "<br />";


// ADD AUTH

echo "<h3>adding auth code</h3>";

echo "adding auth code for user $username";
echo "<br />";

$json = array(
    'username'  => $username,
    'password'  => $password,
    'dev'       => "API test script",
    'device'    => "API device",
    'mode'      => "addauth"
);

$res = json_decode(sendRequest(array('data' => json_encode($json))), true);
$authcode = $res["auth"];


if(isset($res['success'])) {
    echo "auth code, $authcode, added";
    $success++;
} else {
    echo "<h2>ERROR: ".json_encode($res, true)."</h2>";
    $error++;
}
echo "<br />";


//////////////////////////////////////
///////  ADD LINKS

echo "<h3>adding links</h3>";

echo "adding 5 links for $username";
echo "<br />";

$links = array();
for($i=0;$i<5;$i++) {
    $links[$i] = genrand();
}

$json = array(
    'username'  => $username,
    'auth'      => $authcode,
    'dev'       => "API test script",
    'device'    => "API device",
    'mode'      => "update",
    'links'     => array(
        array(
            'id'        => $links[0]
        ),
        array(
            'id'        => $links[1],
            'comments'  => '123'
        ),
        array(
            'id'        => $links[2],
            'comments'  => '321',
            'both'      => true
        ),
        array(
            'id'        => $links[3]
        ),
        array(
            'id'        => $links[4],
            'comments'  => '456'
        )
    )
);

$res = json_decode(sendRequest(array('data' => json_encode($json))), true);


if(isset($res['success'])) {
    echo "added links";
    $success++;
} else {
    echo "<h2>ERROR: ".json_encode($res, true)."</h2>";
    $error++;
}
echo "<br />";






//////////////////////////////////////
///////  CHECK LINKS

echo "<h3>checking links</h3>";

echo "checking 8 links for $username";
echo "<br />";


$json = array(
    'username'  => $username,
    'auth'      => $authcode,
    'dev'       => "API test script",
    'device'    => "API device",
    'mode'      => "read",
    'links'     => array(
        array(
            'id'        => $links[0]
        ),
        array(
            'id'        => $links[1]
        ),
        array(
            'id'        => $links[2]
        ),
        array(
            'id'        => $links[3]
        ),
        array(
            'id'        => $links[4]
        ),
        array (
            'id'        => genrand()
        ),
        array(
            'id'        => genrand()
        ),
        array(
            'id'        => genrand()
        )
    )
);



$res = json_decode(sendRequest(array('data' => json_encode($json))), true);


if(isset($res)) {
    $worked = true;
    $count = 0;
    if(count($res) != 5){
        echo "<h2>ERROR: incorrect number of links found  ".json_encode($res, true)."</h2>";
        $error++;
    } else {

        echo count($res)." links found. ";

        foreach($res as $r){
            if($links[0] == $r['id'] && $r['lastvisit'] > 0 && $r['commentvisit'] < 1) {
                $count++;
            }
            if($links[1] == $r['id'] && $r['lastvisit'] < 1 && $r['commentvisit'] > 0 && $r['comments'] == "123") {
                $count++;
            }
            if($links[2] == $r['id'] && $r['lastvisit'] > 0 && $r['commentvisit'] > 0 && $r['comments'] == "321") {
                $count++;
            }
            if($links[3] == $r['id'] && $r['lastvisit'] > 0 && $r['commentvisit'] < 1) {
                $count++;
            }
            if($links[4] == $r['id'] && $r['lastvisit'] < 1 && $r['commentvisit'] > 0 && $r['comments'] == "456") {
                $count++;
            }
        }

        if($count == 5) {
            echo "all links correct";
            $success++;
        } else {
            echo "<h2>ERROR: ".(5 - $count)." links aren't correct  ".json_encode($res, true)."</h2>";
            $error++;
        }
    }

} else {
    echo "<h2>ERROR: ".json_encode($res, true)."</h2>";
    $error++;
}
echo "<br />";


if($error == 0 && $success == 4) {
    echo "<h3>all tests passed</h3>";
} else {
    echo "<h3>some tests failed. check above</h3>";
}








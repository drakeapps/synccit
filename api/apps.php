<?php



/*
 *
 * Only json works
 *
 * sent data is on POST data variable
 *
 * Only variable is 'platform'
 * ex call:
 * { "platform" : "android" }
 *
 * if platform isn't set or is "all", all apps will be returned
 *
 * platforms are: pc, android, ios
 *
 * returns
 * [ { "name" : "app name", "description" : "app description", "link" : "app link", "platform" : "app platform" }, ...]
 *
 * edit mysql information below
 *
 *
 */

$mysqluser = "synccitapps";
$mysqlpass = "";
$mysqlname = "synccitapps";
$mysqlhost = "localhost";


$mysql = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $mysqlname);
if($mysql->connect_errno) {
    echo "database connection failure <!-- ".$mysql->connect_error." -->";
    die;
}


if(isset($_POST['data'])) {

    $data = json_decode($_POST['data'], true);

    if(isset($data['platform']) && $data['platform'] != "all") {
        $sql = getSQL($data['platform']);
    } else {
        $sql = getSQL();
    }

}
if(!isset($sql)) {
    $sql = getSQL();
}


function getSQL($platform=null) {
    if($platform != null) {
        return "SELECT * FROM `apps` WHERE `platform` = '".$mysql->real_escape_string($platform)."'";
    } else {
        return "SELECT * FROM `apps`";
    }



}

$result = $mysql->query($sql);

$json = array();
$i=0;

while($r = $result->fetch_assoc()) {
    $json[$i]["name"]           = $r['name'];
    $json[$i]["description"]    = $r["description"];
    $json[$i]["link"]           = $r["link"];
    $json[$i]["platform"]       = $r["platform"];
    $i++;
}

echo json_encode($json);

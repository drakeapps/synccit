<?php



// SETUP
// Database info
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "rddtsync";



// Base URL
// if on root, make it blank
// otherwise /foldername with no trailing slash
$baseurl = "/rsync";

// added for password reset emails
// your host no trailing slash
$basehost = "http://localhost";


// API location
$apiloc = "http://localhost/rsync/api/";


// Pretty URLs. Need server configured properly
$prettyurls = false;


// For password reset emails
// using smtp servers
$smtpserver = "smtp.gmail.com";
$smtpauth   = true;
$smtpuser   = "noreply@drakeapps.com";
$smtppass   = "password";
$smtpenc    = "ssl";
$smtpport   = 465;

$fromemail = "noreply@drakeapps.com";





$mysql = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if($mysql->connect_errno) {
	echo "database connection failure <!-- ".$mysql->connect_error." -->";
	die;
}


<?php
declare (strict_types=1);
require_once 'functions.php';

// här börjar spara klass
if(!isset($_GET['classid'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'classid' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['firstname'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'firstname' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['lastname'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'lastname' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['username'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'username' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['password'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'password' saknas"];
    skickaSvar($error, 400);
}

$firstname = filter_input(INPUT_GET , 'firstname' ,FILTER_UNSAFE_RAW);
$firstname = strip_tags($firstname);

$lastname = filter_input(INPUT_GET , 'lastname' ,FILTER_UNSAFE_RAW);
$lastname = strip_tags($lastname);

$username = filter_input(INPUT_GET , 'username' ,FILTER_UNSAFE_RAW);
$username = strip_tags($username);

$classid = filter_input(INPUT_GET, 'classid', FILTER_UNSAFE_RAW);
$classid = strip_tags($classid);

$password = filter_input(INPUT_GET, 'password', FILTER_UNSAFE_RAW);
$password = strip_tags($password);

if($firstname==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'firstname' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($lastname==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'lastname' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($username==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'username' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($classid==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'classid' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($password==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'password' får inte vara tomt"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "SELECT * from elever WHERE anvandarnamn=:anvandarnamn";
$stmt = $db -> prepare($sql);
$stmt -> execute(['anvandarnamn'=>$username]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "användarnamnet '$username' är redan taget"];
    skickaSvar($error, 400);
}

$sql = "INSERT INTO elever (klassID, fornamn, efternamn, anvandarnamn, losenord) VALUES (:klassID, :fornamn, :efternamn, :anvandarnamn, :losenord)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klassID'=>$classid, 'fornamn'=>$firstname, 'efternamn'=>$lastname, 'anvandarnamn'=>$username, 'losenord'=>$password]);

$antalPoster = $stmt -> rowCount();
if($antalPoster===0) {
    $error = new stdClass();
    $error -> error = ["Fel vid spara", "Inga poster sparades", $stmt->errorInfo()];
    skickaSvar($error, 400);
} else {
    $svar = new stdClass();
    $svar -> message = ["Spara lyckades"];
    skickaSvar($svar, 200);
}
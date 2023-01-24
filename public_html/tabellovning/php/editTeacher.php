<?php
declare (strict_types=1);

require_once 'functions.php';

if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

if(!isset($_GET['ID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'ID' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['firstname'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'firstname' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['lastname'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'lastname' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['username'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'username' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['password'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'password' saknas"];
    skickaSvar($error, 400);
}

$ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$ID = trim($ID, $unwanted);

$firstname = filter_input(INPUT_GET, 'firstname', FILTER_UNSAFE_RAW);
$firstname = strip_tags($firstname);

$lastname = filter_input(INPUT_GET, 'lastname', FILTER_UNSAFE_RAW);
$lastname = strip_tags($lastname);

$username = filter_input(INPUT_GET, 'username', FILTER_UNSAFE_RAW);
$username = strip_tags($username);

$password = filter_input(INPUT_GET, 'password', FILTER_UNSAFE_RAW);
$password = strip_tags($password);

if($ID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'ID' får endast bestå av ett heltal och inte vara tom"];
    skickaSvar($error, 400);
}

if($firstname==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'firstname' får inte vara tom"];
    skickaSvar($error, 400);
}

if($lastname==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'lastname' får inte vara tom"];
    skickaSvar($error, 400);
}

if($username==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'username' får inte vara tom"];
    skickaSvar($error, 400);
}

if($password==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'password' får inte vara tom"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "UPDATE larare SET fornamn=:firstname, efternamn=:lastname, anvandarnamn=:username, losenord=:password WHERE ID=:ID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['firstname'=>$firstname, 'lastname'=>$lastname, 'username'=>$username, 'password'=>$password, 'ID'=>$ID]);
$antalPoster = $stmt -> rowCount();
if($antalPoster===0) {
    $error = new stdClass();
    $error -> error = ["Fel vid uppdatera", "Inga poster uppdaterades", $stmt->errorInfo()];
    skickaSvar($error, 400);
} else {
    $svar = new stdClass();
    $svar -> message = ["Uppdatera lyckades"];
    skickaSvar($svar, 200);
}
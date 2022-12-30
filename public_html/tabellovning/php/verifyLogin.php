<?php
declare (strict_types=1);
require_once 'functions.php';

# Kollar så att anropet skickats via POST
if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

# Tar emot POST-input
$username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
$username = strip_tags($username);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
$password = strip_tags($password);
$userType = filter_input(INPUT_POST, 'userType', FILTER_SANITIZE_NUMBER_INT);

# Checkar att indatan är giltig
if($username==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'username' får endast bestå av bokstäver och inte vara tomt"];
    skickaSvar($error, 400);
}

if($password==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'password' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($userType!=0 && $userType!=1 || $userType==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'userType' får endast vara 0 eller 1"];
    skickaSvar($error, 400);
}

# Skapar sql-sats
if($userType===0) {
    $sql = "SELECT losenord FROM larare WHERE anvandarnamn=:username";
} else {
    $sql = "SELECT losenord FROM elever WHERE anvandarnamn=:username";
}

# Kopplar till databas
$db = kopplaDatabas();

$stmt = $db -> prepare($sql);
$stmt -> execute(['username'=>$username]);

# Skickar felmeddelande vid i fel i databasanrop
if(!$stmt -> execute()) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

$out = new stdClass();

# Skickar JSON-respons
if($record = $stmt->fetchObject()) {
    if($record -> losenord === $password) {
        $out -> loginStatus = True;
    } else {
        $out -> loginStatus = False;
    }
}
skickaSvar($out, 200);
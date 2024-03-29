<?php
declare (strict_types=1);
require_once 'functions.php';

# Kollar så att anropet skickats via GET
if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

# Tar emot POST-input
$username = filter_input(INPUT_GET, 'username', FILTER_UNSAFE_RAW);
$username = strip_tags($username);
$password = filter_input(INPUT_GET, 'password', FILTER_UNSAFE_RAW);
$password = strip_tags($password);
$userType = filter_input(INPUT_GET, 'userType', FILTER_SANITIZE_NUMBER_INT);

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

if($userType!=0 && $userType!=1 && $userType!=2 || $userType==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'userType' får endast vara 0 eller 1"];
    skickaSvar($error, 400);
}

# Kopplar till databas
$db = kopplaDatabas();

# Skapar sql-sats
if($userType==0) {
    $sql = "SELECT ID, losenord FROM DB46130.elever WHERE anvandarnamn=:username";
} elseif($userType==1) {
    $sql = "SELECT ID, losenord FROM DB46130.larare WHERE anvandarnamn=:username";
} else {
    $sql = "SELECT ID, losenord FROM DB46130.adminLogin WHERE anvandarnamn=:username";
}

$stmt = $db -> prepare($sql);
$stmt -> execute(['username'=>$username]);

# Skickar felmeddelande vid fel i databasanrop
if(!$stmt -> execute()) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

$out = new stdClass();
$out -> loginStatus = False;

# Skickar JSON-respons
if($record = $stmt->fetchObject()) {
    if(password_verify($password, $record -> losenord)) {
        $out -> loginStatus = True;
    }
    $out -> ID = $record -> ID;
}
skickaSvar($out, 200);
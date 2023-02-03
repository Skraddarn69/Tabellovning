<?php
declare (strict_types=1);
require_once "functions.php";

# Kollar så att anropet skickats via GET
if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

// Skicka felmeddelande om 'teacherID' inte är angivet
if(!isset($_GET['teacherID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "parametern 'teacherID' saknas"];
    skickaSvar($error, 400);
}

$teacherID = filter_input(INPUT_GET, 'teacherID', FILTER_SANITIZE_NUMBER_INT);

// Skicka felmeddelande om 'teacherID' är felaktigt angivet
if($teacherID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'teacherID' är felaktigt angivet"];
    skickaSvar($error, 400);
}

$sql="SELECT ID, klass FROM DB46130.klasser WHERE lararID=:teacherID";

// Koppla databas
$db = kopplaDatabas();

// Hämta data från databas
$stmt = $db -> prepare($sql);
if(!$stmt -> execute(['teacherID'=>$teacherID])) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

// Förbered respons
$out = new stdClass();
$out -> noClasses = true;
if($dbRecords = $stmt->fetchAll()) {
    foreach($dbRecords as $row) {
        $out -> classes[] = $row;
    }
    $out -> noClasses = false;
}

// Skicka respons
skickaSvar($out, 200);
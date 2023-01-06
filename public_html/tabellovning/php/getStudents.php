<?php
declare (strict_types=1);
require_once "functions.php";

// Skicka felmeddelande om 'teacherID' inte är angivet
if(!isset($_GET['classID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "parametern 'teacherID' saknas"];
    skickaSvar($error, 400);
}

$classID = filter_input(INPUT_GET, 'classID', FILTER_SANITIZE_NUMBER_INT);

// Skicka felmeddelande om 'teacherID' är felaktigt angivet
if($classID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'classID' är felaktigt angivet"];
    skickaSvar($error, 400);
}

$sql="SELECT ID, fornamn, efternamn, anvandarnamn FROM elever WHERE klassID=:classID";

// Koppla databas
$db = kopplaDatabas();

// Hämta data från databas
$stmt = $db -> prepare($sql);
if(!$stmt -> execute(['classID'=>$classID])) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

// Förbered respons
$out = new stdClass();
$out -> noStudents = true;
if($dbRecords = $stmt->fetchAll()) {
    foreach($dbRecords as $row) {
        $out -> students[] = $row;
    }
    $out -> noStudents = false;
}

// Skicka respons
skickaSvar($out, 200);
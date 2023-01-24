<?php
declare (strict_types=1);
require_once "functions.php";

# Kollar så att anropet skickats via GET
if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

$sql="SELECT ID, fornamn, efternamn, anvandarnamn FROM larare";

// Koppla databas
$db = kopplaDatabas();

// Hämta data från databas
$stmt = $db -> query($sql);

// Förbered respons
$out = new stdClass();
$out -> noTeachers = true;
if($dbRecords = $stmt->fetchAll()) {
    foreach($dbRecords as $row) {
        $out -> teachers[] = $row;
    }
    $out -> noTeachers = false;
}

// Skicka respons
skickaSvar($out, 200);
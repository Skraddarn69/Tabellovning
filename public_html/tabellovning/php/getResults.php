<?php
declare (strict_types=1);
require_once "functions.php";

# Kollar så att anropet skickats via GET
if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

if(!isset($_GET['ID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'ID' saknas"];
    skickaSvar($error, 400);
}

$ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);

$db = kopplaDatabas();

$sql="SELECT tabell, poang, datum FROM DB46130.resultat" 
    . " WHERE elevID=:ID"
    . " ORDER BY datum";
$stmt = $db -> prepare($sql);
$stmt -> execute(['ID'=>$ID]);

if(!$stmt -> execute()) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

$out = new stdClass();
$out -> noResults = false;

if($dbRecords = $stmt->fetchAll()) {
    $out -> results=[];
    foreach($dbRecords as $row) {
        $out -> results[] = $row;
    }
} else {
    $out -> noResults = true;
}

skickaSvar($out, 200);
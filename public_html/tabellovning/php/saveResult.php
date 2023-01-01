<?php
declare (strict_types=1);
require_once 'functions.php';

if(!isset($_GET['elevID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'elevID' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['tabell'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'tabell' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['poang'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'poang' saknas"];
    skickaSvar($error, 400);
}

$elevID = filter_input(INPUT_GET , 'elevID' ,FILTER_SANITIZE_NUMBER_INT);

$tabell = filter_input(INPUT_GET , 'tabell' ,FILTER_SANITIZE_NUMBER_INT);

$poang = filter_input(INPUT_GET , 'poang' ,FILTER_SANITIZE_NUMBER_INT);

if($elevID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'elevID' får endast bestå av siffror och inte vara tom"];
    skickaSvar($error, 400);
}

if($tabell==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'tabell' får endast bestå av ett heltal från 1-13 och inte vara tom"];
    skickaSvar($error, 400);
}

if($poang==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'poang' får endast bestå av siffror och inte vara tom"];
    skickaSvar($error, 400);
}

if(($tabell<1|$tabell>13)) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'tabell' måste vara ett heltal från 1 till 13"];
    skickaSvar($error, 400);
}

if($tabell==13) {
    $tabell = "blandade";
}

if($poang<0|$poang>10) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'poang' måste vara ett heltal från 0 till 10"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "INSERT INTO resultat (elevID, tabell, poang, datum) VALUES (:elevID, :tabell, :poang, cast(:datum AS datetime))";
$stmt = $db -> prepare($sql);
$stmt -> execute(['elevID'=>$elevID, 'tabell'=>$tabell, 'poang'=>$poang, 'datum'=>date("Y-m-d")]);
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $error = new stdClass();
    $error -> error = ["Fel vid uppdatera", "Inga poster uppdaterades", $stmt->errorInfo()];
    skickaSvar($error, 400);
} else {
    $svar = new stdClass();
    $svar -> message = ["Uppdatera lyckades"];
    skickaSvar($svar, 200);
}
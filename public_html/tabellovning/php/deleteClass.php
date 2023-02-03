<?php
declare (strict_types=1);
require_once 'functions.php';

# Kollar så att anropet skickats via GET
if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

if(!isset($_GET['id'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'id' saknas"];
    skickaSvar($error, 400);
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if($id==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'id' får inte vara tom"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "SELECT * FROM DB46130.elever WHERE klassID=:klassID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klassID'=>$id]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Fel vid radering", "Klassen innehåller elever"];
    skickaSvar($error, 400);
}

$sql = "DELETE FROM DB46130.klasser WHERE ID=:ID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['ID'=>$id]);
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $svar = new stdClass();
    $svar -> result = false;
    $svar -> message = ["Inga poster raderades"];
    skickaSvar($svar, 200);
} else {
    $svar = new stdClass();
    $svar -> result = true;
    $svar -> message = ["Radera lyckades", "$antaPoster poster raderades"];
    skickaSvar($svar, 200);
}
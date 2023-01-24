<?php
declare (strict_types=1);
require_once 'functions.php';

# Kollar så att anropet skickats via GET
if($_SERVER['REQUEST_METHOD']!=="GET") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med GET"];
    skickaSvar($error, 405);
}

if(!isset($_GET['name'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'name' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_GET['teachid'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'teachid' saknas"];
    skickaSvar($error, 400);
}

$name = filter_input(INPUT_GET, 'name' ,FILTER_UNSAFE_RAW);
$name = strip_tags($name);

if($name==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'name' får inte vara tom"];
    skickaSvar($error, 400);
}

$teachid = filter_input(INPUT_GET, 'teachid', FILTER_SANITIZE_NUMBER_INT);

if($teachid==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'teachid' får inte vara tom"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "INSERT INTO klasser (klass, lararID) VALUES (:klass, :lararID)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$name, 'lararID'=>$teachid]);
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
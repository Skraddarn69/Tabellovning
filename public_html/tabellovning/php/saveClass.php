<?php
declare (strict_types=1);
require_once 'funktioner.php';

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
    $error -> error = ["Felaktigt anrop", "'klass' får inte vara tom"];
    skickaSvar($error, 400);
}

$teachid = filter_input(INPUT_GET, 'teachid', FILTER_SANITIZE_NUMBER_INT);

if($teachid==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'teachid' får inte vara tom"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "SELECT * from klasser WHERE klass=:klass";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Klassen '$klass' finns redan"];
    skickaSvar($error, 400);
}

$sql = "INSERT INTO klasser (klass, lararID) VALUES (:klass, :lararID)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$name, 'lararID'=>$teachid]);
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $error = new stdClass();
    $error -> error = ["Fel vid spara", "Inga poster sparades", $stmt->errorInfo()];
    skickaSvar($error, 400);
} else {
    $svar = new stdClass();
    $svar -> message = ["Spara lyckades"];
    skickaSvar($svar, 200);
}
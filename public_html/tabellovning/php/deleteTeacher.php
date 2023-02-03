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
    $error -> error = ["Felaktig indata", "'id' saknas"];
    skickaSvar($error, 400);
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$db = kopplaDatabas();

$sql = "DELETE FROM DB46130.larare WHERE ID=:id";
$stmt = $db -> prepare($sql);
$stmt -> execute(['id'=>$id]);
$antalPoster = $stmt -> rowCount();

if($antalPoster===0) {
    $svar = new stdClass();
    $svar -> result = false;
    $svar -> message = ["Inga poster raderades"];
} else {
    $svar = new stdClass();
    $svar -> result = true;
    $svar -> message = ["Radera lyckades", "$antalPoster poster raderades"];
}

skickaSvar($svar, 200);
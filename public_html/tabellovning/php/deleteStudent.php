<?php
declare (strict_types=1);
require_once 'functions.php';

if(!isset($_GET['id'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'id' saknas"];
    skickaSvar($error, 400);
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$db = kopplaDatabas();

$sql = "DELETE FROM resultat WHERE elevID=:id";
$stmt = $db -> prepare($sql);
$stmt -> execute(['id'=>$id]);
$antalPoster = $stmt -> rowCount();

$sql = "DELETE FROM elever WHERE ID=:id";
$stmt = $db -> prepare($sql);
$stmt -> execute(['id'=>$id]);
$antalPoster = $antalPoster + $stmt -> rowCount();

var_dump($antalPoster);
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
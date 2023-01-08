<?php
declare (strict_types=1);
require_once "functions.php";

if(!isset($_GET['ID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'ID' saknas"];
    skickaSvar($error, 400);
}

$ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);

$db = kopplaDatabas();

$sql="SELECT tabell, poang, datum FROM resultat" 
    . " WHERE elevID=:ID"
    . " ORDER BY datum";
$stmt = $db -> prepare($sql);
$stmt -> execute(['ID'=>$ID]);

if(!$stmt -> execute()) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

if($dbRecords = $stmt->fetchAll()) {
    $out = new stdClass();
    $out -> results=[];
    foreach($dbRecords as $row) {
        $out -> results[] = $row;
    }
    skickaSvar($out, 200);
} else {
    $error = new stdClass();
    $error -> error = ["Fel vid hämtning",
            "Inga poster returnerades"];
    skickaSvar($error, 400);
}
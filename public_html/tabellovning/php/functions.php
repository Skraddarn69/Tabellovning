<?php

function kopplaDatabas():PDO {
    $dbHost = "localhost";
    $dbDSN = "DB46130";
    $dbUser = "LinusD";
    $dbPassword = "RokkABiltemA69";

    try {
        $db = new PDO("mysql:host=$dbHost;dbName=$dbDSN", $dbUser, $dbPassword);
    } catch (PDOException $ex) {
        $err = new stdClass();
        $err->error = [$ex->getMessage()];
        skickaSvar($err, 401);
    }
    return $db;
}

function skickaSvar(stdClass $info, int $svarsKod): void {

    header(hamtaHeader($svarsKod));
    echo json_encode($info, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    exit;
}

function hamtaHeader(int $svarsKod):string {
    $retur = ";Content-type:application/json:charset=utf-8";
    switch($svarsKod) {
        case 200:
            $retur = "200 OK" . $retur;
            break;
        case 400:
            $retur = "400 Bad Request" . $retur;
            break;
        case 401:
            $retur = "401 Unauthorized";
            break;
        case 405:
            $retur = "405 Method not allowed" . $retur;
            break;
        default:
            $retur = "500 Invalid Header" . $retur;
            break;
    }

    $retur = "HTTP/1.1 " . $retur;

    return $retur;
}
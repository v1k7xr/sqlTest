<?php
$separator = "------------------------------";

$dbCD = parse_ini_file("bd.ini"); #data base Connection Data
$unsavesql = parse_ini_file("query_unsave.ini");
$savesql = parse_ini_file("query_save.ini");

$pdo = new PDO("pgsql:dbname=" . $dbCD['dbname'] . ";host=" . $dbCD['host'], $dbCD['username'], $dbCD['password']);

function getDataFromDB($sqlString, $sqlArgue) {
    global $pdo;

    $stmt = $pdo->prepare($sqlString);
    $stmt->execute(['arg1' => $sqlArgue]);
    
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
}

function getDataFromDBUnsave($sqlString) {
    $result = pg_query($sqlString) or die('query error: ' . pg_last_error());

    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        var_dump($line);
    }

}

function separateInfo($textArgue, $textValue) {
    global $separator;
    echo($separator . $separator . "\n");
    echo($textArgue . " = " . $textValue . "\n");
}

echo($separator . "WITH PDO" . $separator . "\n");
echo("news_id = " . $savesql['query1'] . "\n");

$sqlString = 'SELECT * FROM news WHERE news_id =:arg1';

var_dump(getDataFromDB($sqlString, $savesql['query1']));

separateInfo("news_id", $unsavesql['query1']);

var_dump(getDataFromDB($sqlString, $unsavesql['query1']));

separateInfo("news_text", $savesql['query1']);

$sqlString = 'SELECT * FROM news WHERE news_text =:arg1';

var_dump(getDataFromDB($sqlString, $savesql['query2']));

separateInfo("news_text", $unsavesql['query1']);

var_dump(getDataFromDB($sqlString, $unsavesql['query2']));

echo($separator . "WITHOUT PDO" . $separator . "\n");
separateInfo("news_id", $savesql['query1']);

$connectionString = "host=" . $dbCD['host'] . " dbname=" . $dbCD['dbname'] . " user=" . $dbCD['username'] . " password=" . $dbCD['password'];
$dbconn = pg_connect($connectionString);

$sqlString = 'SELECT * FROM news WHERE news_id = ' . $savesql['query1'];
getDataFromDBUnsave($sqlString);

separateInfo("news_id", $unsavesql['query1']);

$sqlString = 'SELECT * FROM news WHERE news_id = ' . $unsavesql['query1'];
getDataFromDBUnsave($sqlString);

separateInfo("news_text", $savesql['query2']);

$sqlString = "SELECT * FROM news WHERE news_text = '" . $savesql['query2'] . "'";
getDataFromDBUnsave($sqlString);

separateInfo("news_text", $unsavesql['query2']);

$sqlString = "SELECT * FROM news WHERE news_text = " . $unsavesql['query2'];
getDataFromDBUnsave($sqlString);
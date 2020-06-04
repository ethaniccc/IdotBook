<?php
$accountName = $argv[1];
$database = new \SQLite3('LoginData.db');
$checkExists = $database->query("SELECT * FROM realUsers WHERE userName = '$accountName';");
$result = $checkExists->fetchArray(SQLITE3_ASSOC);
if(!empty($result)){
    $username = $result["userName"];
    $password = $result["userPass"];
    $database->query("DELETE FROM realUsers WHERE userName = '$username' and userPass = '$password'");
    echo "Account deleted.\n";
} else {
    echo "Account not found.\n";
}
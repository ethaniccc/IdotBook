<!DOCTYPE html>
<head>
    <title>Authenticating Login...</title>
    
</head>

<?php

require 'ProfanityCheck.php';

session_start();
if(!file_exists('databases')) mkdir('databases');
$database = new \SQLite3('databases/LoginData.db');

if(!isset($_POST['username'], $_POST['password'])){
    exit('Something went wrong, retry entering both the username and password fields!');
}
$database->exec("CREATE TABLE IF NOT EXISTS realUsers(userName TEXT PRIMARY KEY, userPass TEXT);");
$username = $_POST['username'];
$password = $_POST['password'];

$userInfo = $database->query("SELECT * FROM realUsers WHERE userName = '$username' and userPass = '$password';");
$userArray = $userInfo->fetchArray(SQLITE3_ASSOC);
if($userArray == false){
    if(!empty($database->query("SELECT * FROM realUsers WHERE userName = '$username';")->fetchArray(SQLITE3_ASSOC))){
        session_regenerate_id();
        $_SESSION['loggedin'] = "falp";
        header('Location: index.php');
        return;
    }
    // To save my time, I'm blocking all usernames with spaces in them.
    if(preg_match('/\s/',$username)){
        echo "<h1>Internal Server Error!</h1><p>There was a space in your username, please make sure there are no spaces in your name!</p>";
        return;
    }
    // I'm also going to block usernames with special characters to save my time.
    if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)){
        echo "<h1>Internal Server Error!</h1><p>There was a special character in your username, please make sure there are no special characters in your name!</p>";
        return;
    }

    if(ProfanityCheck::hasProfanity($username)){
        echo '<h1 align="center">Profanity Detected!</h1>';
        echo '<p align="center">Your username was detected for having profanity and therefore, blocked.</p>';
        exit();
        return;
    }
    
    $newUserInfo = $database->prepare("INSERT OR REPLACE INTO realUsers (userName, userPass) VALUES (:userName, :userPass);");
    $newUserInfo->bindValue(":userName", $username);
    $newUserInfo->bindValue(":userPass", $password);
    $newUserInfo->execute();
    session_regenerate_id();
    $_SESSION['loggedin'] = "new";
    $_SESSION['name'] = $_POST['username'];
    header('Location: home.php');
} else {
    if($password != $userArray["userPass"]){
        session_regenerate_id();
        $_SESSION['loggedin'] = "falp";
        header('Location: index.php');
    } else {
        session_regenerate_id();
        $_SESSION['loggedin'] = "yes";
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['password'] = $password;
        header('Location: home.php');
    }
}
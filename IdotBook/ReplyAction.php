<?php

$filePath = $_POST["file"];
$replyMessage = $_POST["reply"];
$replyUser = $_POST['reply_name'];
$authPass = $_POST["password"];
$database = new \SQLite3('databases/LoginData.db');
$userInfo = $database->query("SELECT * FROM realUsers WHERE userName = '$replyUser' and userPass = '$authPass'");
$userArray = $userInfo->fetchArray(SQLITE3_ASSOC);
if($userArray == false){
    echo "<h1>Fatal Error!</h1>";
    echo "<p>The inspect username to hyjack a user bug has been patched. Truck off you big noob!</p>";
    return;
} else {
    $content = "\nReplier: $replyUser" . "\nReply: $replyMessage" . "\n------";
    $postFile = fopen($filePath, "a");
    fwrite($postFile, $content);
    fclose($postFile);
    // ------
    // Replier: ethaniccc
    // Reply: lmao
    // ------
    // Replier: mm545
    // Reply: XD
    // ------
    session_regenerate_id();
    $_SESSION['loggedin'] = "yes";
    $_SESSION['name'] = $replyUser;
    $_SESSION['password'] = $authPass;
    header('Location: home.php');
}
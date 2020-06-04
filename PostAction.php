<?php

var_dump($_POST);
$previousTime = microtime(true);
$database = new \SQLite3('databases/LoginData.db');

$title = $_POST["title"];
$realUser = $_POST["real_author"];
$content = $_POST["post_text"];
if(ctype_space($title)) die("Invalid title was given. Your post has been blocked to prevent server errors.");
if(ctype_space($content)) die("Invalid post was given. Your post has been blocked to save server storage.");

if(ctype_alpha($content)){
    echo "<h1>Spam Prevention!</h1>";
    echo "<p>Because this post only contains alphabetical characters with no spaces, it was considered as spam. Contact my Discord (@ethaniccc#0001) if you feel as if this was done in error!</p>";
    return;
}

if(ctype_digit($content)){
    echo "<h1>Spam Prevention!</h1>";
    echo "<p>Because this post only contains numerical characters with no spaces, it was considered as spam. Contact my Discord (@ethaniccc#0001) if you feel as if this was done in error!</p>";
    return;
}

// I'm not going to worry about this right now.
// Ok time to worry wtf is wrong with people.
$profanity = [
    "fuck", "bitch", "cunt", "slut", "whore",
    "nigga", "nigger"
];

$realName = substr($realUser, 35);
$authPass = $_POST['password'];

$userInfo = $database->query("SELECT * FROM realUsers WHERE userName = '$realName' and userPass = '$authPass';");
$userArray = $userInfo->fetchArray(SQLITE3_ASSOC);
if($userArray == false){
    echo "<h1>Critical Error!</h1>";
    echo "Your action has been blocked due to incorrect credentials!";
    return;
}

@mkdir('posts', 0777);
@mkdir('posts/global', 0777);
@mkdir('posts/' . $realName, 0777);
$postCount = count(scandir('posts/' . $realName)) - 2 + 1;
$content = "Title: $title\nAuthor: $realName\nContent: $content\n\nReplies:\n------";
$userFile = fopen("posts/$realName/$realName" . "$postCount.txt", "w");
fwrite($userFile, $content);
fclose($userFile);
$globalFile = fopen("posts/global/$realName" . "$postCount.txt", "w");
fwrite($globalFile, $content);
fclose($globalFile);

session_regenerate_id();
$_SESSION['loggedin'] = "posted";
$_SESSION['post_time'] = round(microtime(true) - $previousTime, 3);
$_SESSION['name'] = $realName;
header('Location: home.php');
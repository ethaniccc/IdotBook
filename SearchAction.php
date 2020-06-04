
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>IdotBook</title>
		<link href="styles2.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>IdotBook</h1>
				<a href="index.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Search Results</h2>
			<form align="center" method="post" action="SearchAction.php">
				<h1><u>Search For Thread By Name</u></h1>
				<input type="text" placeholder="Search.." name="search" id="search">
  				<button type="submit"><i class="fa fa-search"></i></button>
			</form>
			<h1 align="center"><u>This feature is disabled.</u></h1>
        </div>
            
</html>

<?php

return;

class StringUtils{

    public static function after($characters, $inthat){
        if (!is_bool(strpos($inthat, $characters)))
        return substr($inthat, strpos($inthat,$characters)+strlen($characters));
    }

    public static function before($characters, $inthat){
        return substr($inthat, 0, strpos($inthat, $characters));
    }

    public static function before_last($characters, $inthat){
        return substr($inthat, 0, strripos($inthat, $characters));
    }

}

$termSearched = $_POST["search"];
$files = glob('posts/global/*.txt');
usort($files, function($a, $b){
    return filemtime($a) < filemtime($b);
});
if(count($files) !== 0)
echo '<div class="content">';
foreach($files as $file){
    if(!is_dir($file)){
		echo StringUtils::after("Title: ", file($file)[0]);
        if(strpos(StringUtils::after("Title: ", file($file)[0]), $termSearched)){
            $content = StringUtils::before("Replies:", StringUtils::after("Content: ", file_get_contents($file)));
			$fileLines = file($file);
			$title = StringUtils::after("Title: ", $fileLines[0]);
			$author = StringUtils::after("Author: ", $fileLines[1]);
			$replies = explode("------\n", StringUtils::after("------\n", file_get_contents($file)));
			echo "<h2>$title | $author</h2>";
			echo '<p>' . $content . '</p>';
			echo "<h3><u>Replies</u></h3>";
			echo '<div style="height:120px;width:995px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">';
			foreach($replies as $reply){
				$replyGuy = StringUtils::before("Reply: ", StringUtils::after("Replier: ", $reply));
				$replyMessage = str_replace("------", "", StringUtils::after("Reply: ", $reply));
				echo "<h4><b><u>$replyGuy</u></b></h4>";
				if($replyMessage != "") echo '<p>' . $replyMessage . '</p>';
            }
            echo '</div>';
		} else {
			echo "";
		}
    }
}
echo '</div>';
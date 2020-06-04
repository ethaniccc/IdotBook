<?php
session_start();
// If the user is not logged in redirect to the login page they go bye bye!
if(!isset($_SESSION['loggedin'])){
	header('Location: index.php');
	exit;
}
function exit_session() : void{
	header('Location: index.php');
	exit;
}

$threadTitles = [];

$username = $_SESSION['name'];
$password = $_SESSION['password'];

$files = glob('posts/global/*.txt');
usort($files, function($a, $b) {
    return filemtime($a) < filemtime($b);
});
foreach($files as $file){
	$fileLines = file($file);
	$title = StringUtils::after("Title: ", $fileLines[0]);
	array_push($threadTitles, $title);
}

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
?>

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
			<h2>Home Page</h2>
			<?php
                switch($_SESSION['loggedin']){
                    case "yes":
                        echo "<p>Welcome back, " . $_SESSION['name'] . "!</p>";
                    break;
                    case "new":
                        echo "<p>Hello there, " . $_SESSION['name'] . "! It appears to be your first time here!</p>";
					break;
					case "posted":
						echo "<p><b>Congratulations! Your post is now up on IdotBook!</b></p>";
						if(isset($_SESSION['post_time'])) echo "<p>(It took " . $_SESSION['post_time'] . " seconds for your post to go up!</p>";
					break;
					default:
						echo "<p>Welcome back, " . $_SESSION['name'] . "!</p>";
						echo "<br><p>It took " . $_SESSION['post_time'] . "s to post!</p>";
					break;
                }
            ?>
			<form align="center" method="post" action="SearchAction.php">
				<h1><u>Search For Thread By Name</u></h1>
				<input type="text" placeholder="Search.." name="search" id="search">
  				<button type="submit"><i class="fa fa-search"></i></button>
			</form>
            <form method="post" action="PostAction.php">
				<p><b>Make a post!</b></p>
            	<textarea id="title" name="title" placeholder="Post Title" rows="4" cols="50" style="resize: none; margin: 0px; width: 997px; height: 30px;"></textarea>
            	<textarea style="display:none;" readonly name="real_author" placeholder="" id="real_author" rows="4" cols="50" style="resize: none; margin: 0px; width: 0px; height: 0px;">Real Author (will not be exposed): <?php echo($_SESSION['name']) ?></textarea>
            	<textarea style="display:none;" readonly name="password" placeholder="" id="password" rows="4" cols="50" style="resize: none; margin: 0px; width: 0px; height: 0px;"><?php echo($_SESSION["password"]) ?></textarea>
            	<textarea id="post_text" name="post_text" placeholder="Say something?" rows="4" cols="50" style="resize: none; margin: 0px; width: 997px; height: 122px;"></textarea>
				<input type="submit" value="Post" id="post_button">
				<p><b>Warning: When you post something, there is currently no deletion system,
				so you have to contact me directly to delete a post.</b></p>
            </form>
		</div>
		<div class="content">
				<h1><u>Current Posts</u></h1>
				<?php
					if(file_exists('posts')){
						$files = glob('posts/global/*.txt');
						usort($files, function($a, $b) {
    						return filemtime($a) < filemtime($b);
						});
						foreach($files as $file){
							if(!is_dir($file)){
								$content = StringUtils::before("Replies:", StringUtils::after("Content: ", file_get_contents($file)));
								$fileLines = file($file);
								$title = StringUtils::after("Title: ", $fileLines[0]);
								$author = StringUtils::after("Author: ", $fileLines[1]);
								$replies = explode("------\n", StringUtils::after("------\n", file_get_contents($file)));
								echo "<h2>$title | $author</h2>";
								array_push($threadTitles, $title);
								echo '<p>' . $content . '</p>';
								echo "<h3><u>Replies</u></h3>";
								echo '<div style="height:120px;width:995px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">';
								foreach($replies as $reply){
									$replyGuy = StringUtils::before("Reply: ", StringUtils::after("Replier: ", $reply));
									$replyMessage = str_replace("------", "", StringUtils::after("Reply: ", $reply));
									echo "<h4><b><u>$replyGuy</u></b></h4>";
									if($replyMessage != "") echo '<p>' . $replyMessage . '</p>';
								}
								echo '<form method="post" action="ReplyAction.php">';
								echo '<textarea style="display:none;" readonly name="reply_name" placeholder="" id="reply_name" rows="4" cols="50" style="resize: none; margin: 0px; width: 0px; height: 0px;">' . $_SESSION['name'] . '</textarea>';
								echo '<textarea style="display:none;" readonly name="password" placeholder="" id="password" rows="4" cols="50" style="resize: none; margin: 0px; width: 0px; height: 0px;">' . $_SESSION['password'] . '</textarea>';
								echo '<textarea style="display:none;" readonly name="file" placeholder="" id="file" rows="4" cols="50" style="resize: none; margin: 0px; width: 0px; height: 0px;">' . $file . '</textarea>';
								echo '<br><textarea id="reply" name="reply" placeholder="Reply To Post" rows="4" cols="50" style="resize: none; margin: 0px; width: 997px; height: 20px;"></textarea>';
								echo '<input type="submit" value="Reply" id="post_button">';
								echo '</form>';
							} else {
								echo "";
							}
							echo '</div>';
						}
					}
				?>
		</div>
	</body>
</html>
<?php
	require_once 'includes/global.inc.php';
	$head="<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
	$html="";
	if (isset($_SESSION['user'])){
		$pdf=$_GET['pdf'];
		$user = unserialize($_SESSION['user']);
		$imgsrc = "img/".$pdf."_1.jpg";
		$html .="<div class=\"splitbox\"\">";
			$html .="<div class=\"imageViewer\"><img id=\"viewer\" src=\"$imgsrc\">\n";
				$html .="<div class=\"nav_buttons\">";
					$html .="<button class=\"previous\" "; //the javascript bound to the onclick event just changes the image from title_{i}.jpg to title_{i-1}.jpg - we will use an ajax scheme later, and only change on the callback if the image exists.
					$html .=		"onclick=\"var x=getElementById('viewer').src.split(/(\d)+.jpg/);console.log(x);var n=parseInt(x[1])-1;getElementById('viewer').src=x[0]+n+'.jpg';\">Previous</button>";
					$html .="<button class=\"next\" ";
					$html .=		"onclick=\"var x=getElementById('viewer').src.split(/(\d)+.jpg/);console.log(x);var n=parseInt(x[1])+1;getElementById('viewer').src=x[0]+n+'.jpg';\">Next</button>";
				$html .="</div>";
			$html .="</div>";
			$html .="<div class=\"commentViewer\">";
				//TODO: add "comments" from db for this page (dependency: comment table in DB)
				//For now, insert dummy content
				$html .="<ul>";//foreach comment, put it in a li element
				$html .="<li class=\"comment\"><span class=\"comment_name\">User: </span>Example comment1...</li>";
				$html .="<li class=\"comment\"><span class=\"comment_name\">User2: </span>another bit of mock comment text is here too!</li>";
				$html .="</ul>";
				$html .="<div class=\"commentbox\">";
					$html .="<textarea>Write your own comment...</textarea><br>";
					$html .="<button class=\"post_button\">Post</button>";
				$html .="</div>";
			$html .="</div>";
			$html .="<div class=\"clear\">";
		$html .="</div>";

	}else{
		$html.="<a href=\"/CS3003S/OIPLSV/login.php\">Login</a>";
		$html.=" or <a href=\"/CS3003S/OIPLSV/register.php\">Register</a><br>\n";
		$html.="<img src=\"img/home.png\">\n";
	}

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $head ?>
</head>
<body>
	<?php
	include 'includes/header.inc.php';
	echo $html ?>
</body>
</html>

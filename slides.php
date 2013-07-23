<?php
	require_once 'includes/global.inc.php';
	$head="<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
	$html="";
	if (isset($_SESSION['user'])){
		$pdf=$_GET['pdf'];
		$user = unserialize($_SESSION['user']);
		$html .="<a href=\"/CS3003S/OIPLSV/logout.php\">Logout</a>.";
		$html .="<p>You are logged in as ".$user->username."</p>";
		$imgsrc = "img/".$pdf."_1.jpg";
		$html .="<div class=\"splitbox\"\">";
			$html .="<div class=\"imageViewer\"><img id=\"viewer\" src=\"$imgsrc\">\n";
				$html .="<div class=\"nav_buttons\">";
					$html .="<button class=\"previous\" ";
					$html .=		"onclick=\"var x=getElementById('viewer').src.split(/(\d)+.jpg/);console.log(x);var n=parseInt(x[1])-1;getElementById('viewer').src=x[0]+n+'.jpg';\">Previous</button>";
					$html .="<button class=\"next\" ";
					$html .=		"onclick=\"var x=getElementById('viewer').src.split(/(\d)+.jpg/);console.log(x);var n=parseInt(x[1])+1;getElementById('viewer').src=x[0]+n+'.jpg';\">Next</button>";
				$html .="</div>";
			$html .="</div>";
			$html .="<div class=\"commentViewer\">";
				//TODO: add comments from db for this page.
				$html .="<ul>";//foreach comment, put it in a li element
				$html .="<li class=\"comment\"><span class=\"comment_name\">User: </span>Example comment1...</li>";
				$html .="<li class=\"comment\"><span class=\"comment_name\">User2: </span>another bit of mock comment text is here too!</li>";
				$html .="</ul>";
				$html .="<div class=\"commentbox\">";
					$html .="<textarea>Write your own comment...</textarea>";
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
	<?php echo $html ?>
</body>
</html>

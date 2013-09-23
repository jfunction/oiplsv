<?php
	require_once('includes/global.inc.php'); 
	require_once('FirePHPCore/fb.php');
	
	$head="<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
	
	$html="";
	if (isset($_SESSION['user']) && isset($_SESSION['logged_in'])){
		fb($_SESSION,'User is logged in');
		$user = unserialize($_SESSION["user"]);
		fb($user,'User from index.php');
		$html .="<p>Here's the list of all lectures:</p>";
		$html .="<ul>";//todo: get these from a db or from disk... db is better
		$db = new DB();
		$pdfdocs = $db->select("PDFDocument","");//everything from pdf table
		fb($pdfdocs,'PDFs from the database, index.php');
		//list them out
		foreach($pdfdocs as $i => $pdf){
			$html .="<li><a href=\"slides.php?pdfdocument_id=".$pdf["pdfdocument_id"]."&pdfslide=1\">".$pdf["title"]."</a></li>";
		}
		$html .="</ul><br>\n";
		//only certain users can upload
		if ("admin"==$user->role || "lecturer"==$user->role){
			$html .="<a href=\"upload.php\">Upload PDF</a>";
		}
	}else{
		fb('User is NOT logged in');
		$html.="<a href=\"/CS3003S/OIPLSV/login.php\"><h1>Login</h1></a><br>";
		$html.="<h1> or </h1><br><a href=\"/CS3003S/OIPLSV/register.php\"><h1>Register</h1></a><br><br>\n";
		$html.="<footer><img src=\"img/home.png\"></footer>";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<?php echo $head; ?>
	</head>
	<body>
		<?php 
		include 'includes/header.inc.php';
		echo $html ?>
	</body>
</html>

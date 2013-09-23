<?php
	require_once('includes/global.inc.php');
	require_once('FirePHPCore/fb.php');
	$head="<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
	$head.="<link rel=\"stylesheet\" type=\"text/css\" href=\"css/colorbox.css\">";
	$head.="<script type=\"text/javascript\" src=\"js/jquery-1.10.2.js\"></script>";
	$head.="<script type=\"text/javascript\" src=\"js/jquery.colorbox.js\"></script>";
	$html="";
	if (isset($_SESSION['user']) and isset($_GET['pdfdocument_id'])){
		$pdfdocument_id=$_GET['pdfdocument_id'];
		$current_slide=isset($_GET['pdfslide'])?$_GET['pdfslide']:1;
		$db = new DB();
		$numslides = count($db->select('PDFSlide',"pdfdocument_id = $pdfdocument_id"));
		
		$next_url = "slides.php?pdfdocument_id=$pdfdocument_id&pdfslide=".($current_slide < $numslides ? $current_slide+1:$current_slide);
		$previous_url = "slides.php?pdfdocument_id=$pdfdocument_id&pdfslide=".($current_slide > 1 ? $current_slide-1 : 1);
		$head.='<script>$(document).keydown(function(e){';
		$head.='if (e.keyCode == 37) {document.location.href="'.$previous_url.'";}';
		$head.='if (e.keyCode == 39) {document.location.href="'.$next_url.'";}';
		$head.='});</script>';
		
		$pdfdocument = $db->select('PDFDocument',"'pdfdocument_id' = $pdfdocument_id");
		$pdftitle = $pdfdocument[0]['title'];
		$pdfslide_db = $db->select('PDFSlide',"pdfdocument_id = $pdfdocument_id AND pdfslide_order = $current_slide");
		$pdfslide_db = $pdfslide_db[0];
		fb($pdfslide_db,'From the DB yo');
		//~ $html.="<script type=\"text/javascript\">window.image_title='img/$pdftitle';window.numslides=$numslides;window.current_slide=1;</script>";
		$user = unserialize($_SESSION['user']);
		$imgsrc = "img/".$pdftitle."_".$current_slide.".jpg";
		$html .="<div class=\"splitbox\"\">";
			$html .="<div class=\"imageViewer\"><img id=\"viewer\" src=\"$imgsrc\">\n";
				fb($current_slide,'current');
				fb($numslides,'numslides');
				if ($current_slide > 1){
					$html.="<a href=\"$previous_url\">Back</a>";
				}
				if ($current_slide < $numslides){
					$html.="<a href=\"$next_url\">Next</a>";
				}
			$html .="</div>";
			$html .="<div class=\"commentViewer\">";
				//TODO: add "comments" from db for this page (dependency: comment table in DB)
				$comments = $db->select('UserComment',"pdfslide_id = '".$pdfslide_db['pdfslide_id']."'");
				fb($comments,"Comments!");
				$html .="<ul class=\"comments\">";//foreach comment, put it in a li element
				foreach($comments as $comment){
					$user = $db->select('User',"user_id = ".$comment['user_id']);
					$user=new User($user[0]);
					$html .="<li class=\"comment\">";
					$html .="<span class=\"comment_name\">$user->username</span>: ".$comment['comment_text']."<br>";
					$html .="<a class=\"colorbox\" href=\"addresponse.php?pdfdocument_id=$pdfdocument_id&pdfslide=$current_slide&comment_id=".$comment['usercomment_id']."\">reply</a>"; //<--reply
					$html .="";//<--rate
					$responses = $db->select('UserResponse',"usercomment_id = '".$comment['usercomment_id']."'");
					$html .="<ul class=\"responses\">";
					foreach($responses as $response){
						$response_user = $db->select("User","user_id = ".$response['user_id']);
						$response_user=$response_user[0];
						$response_user['username'];
						$response_text = $response['response_text'];
						$html .= "<li><span class=\"response_username\">".$response_user['username']."</span>: $response_text</li>";
					}
					$html .="</ul>";
					$html .="</li>";
					//now get the responses for this guy
				}
				$html .="</ul>";
				$html .="<form action=\"addcomment.php?pdfdocument_id=$pdfdocument_id&pdfslide=$current_slide&pdfslide_id=".$pdfslide_db['pdfslide_id']."\" method=\"post\">";
				$html .="<div class=\"commentbox\">";
					$html .="<textarea placeholder=\"Write your own comment...\" name=\"comment_text\">Write your own comment...</textarea><br>";
					$html .="<input type=\"hidden\" name=\"pdfdocument_id\" value=\"$pdfdocument_id\"><br>";
					$html .="<input type=\"hidden\" name=\"pdfslide\" value=\"$current_slide\"><br>";
					$html .="<input type=\"hidden\" name=\"pdfslide_id\" value=\"".$pdfslide_db['pdfslide_id']."\"><br>";
					$html .="<button class=\"post_button\">Post</button>";
				$html .="</form>";
				$html .="</div>";
				$html .="</div>";
			$html .="</div>";
			$html .="<div class=\"clear\">";
		$html .="</div>";
		$html .="<script>$('a.colorbox').colorbox({width:500,height:500});</script>";

	}else{
		$html.="<a href=\"/CS3003S/OIPLSV/login.php\">Login</a>";
		$html.=" or <a href=\"/CS3003S/OIPLSV/register.php\">Register</a><br>\n";
		$html.="<img src=\"img/home.png\">\n";
		header('Location: index.php');
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

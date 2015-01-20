<html>
<head>

<title>Music G2M</title>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.scrollgress.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>

<script>
$(document).ready(function(){
	playVideo();
});

	
	function dettaglioAlbum(artist,album){
		var request =  'artist='+artist+'&album='+album;
		$.ajax({
		    url : "risultatoAjax.php" ,
		    data : request, 
		    type : "GET",
		    success : function (data,stato) {
		        $("#detAlbum").html(data);
		    },
		    error : function (richiesta,stato,errori) {
		        alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
		    }
		});
	}

	var tag;
	var firstScriptTag;
	var response;

	function playVideo(){
		response = document.getElementById("idVideo").innerHTML;
		tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";
		firstScriptTag = document.getElementsByTagName('head')[0];
		firstScriptTag.appendChild(tag);
	}
	 function onYouTubeIframeAPIReady() {
			var player;
		//	alert(response);
			player = new YT.Player('player', {
				height : '390',
				width : '640',
				videoId : response,
				events : {
					'onReady' : onPlayerReady
				}
			});
		}
		
		function onPlayerReady(event) {
			event.target.playVideo();
		}

		function stopVideo() {
			player.stopVideo();
		}

	</script>
</head>
<body class="index">

		<!-- Header -->
			<header id="header" class="reveal">
				<h1 id="logo"><a href="home.html">G2Music</a></h1>
				<nav id="nav">
					<ul>
						<li class="current"><a href="home.html">Welcome</a></li>
					</ul>
				</nav>
			</header>
			<article id="main">
			<section id="contenitore"class="wrapper style3 container special">
			
		<?php
		
		session_start();
		
		include ("WrapperMusixMatch.php");
		include ("WrapperYouTube.php");
		include ("WrapperLastFm.php");
		include ("WrapperGoogleImage.php");
		

		
		if (isset ( $_GET ['artist'] ) && isset ( $_GET ['song'] )) {
			$artist = $_GET ['artist'];
			$song = $_GET ['song'];
		}
		
		if (isset($_SESSION[$artist])){
			$image=$_SESSION[$artist];
		}
		else {
			$wrapperGoogleImage = new WrapperGoogleImage ( $artist );
			$json = $wrapperGoogleImage->get_url_contents ();
				
			$data = json_decode ( $json );
				
			$source=$data->responseData->results [0]->url;
				
			$image="<img  src=". $source."></img>";
			$_SESSION[$artist]=$image;
		}
		
		echo "
			<header class=\"major\">
		<span class='artista'>$artist</span>
		<div class=\"image\" id='immagine'> " . $image.  "</div>
		<span class='canzone'>$song</span>
		</header> 
		<div class=\"row\">";
		
		$wrapperMusixMatch = new WrapperMusixMatch ( $artist, $song, '' );
		$textSong = $wrapperMusixMatch->scrapingText ();
		if ($textSong == "0") {
			$textSong="<p>Text not found</p><img src=\"images/cry.png\">";
		} 
		
		echo "<div class='6u 12u(narrower)'  id='testo' ><pre> " . $textSong . "</pre></div>";
			
		
			
		$request = $artist . " " . $song;
		$wrapperYouTube = new WrapperYouTube ( $request );
		$idVideo = $wrapperYouTube->getIdByName ();
		echo "<div id='idVideo' hidden>" . $idVideo . "</div>";
		
		?>
		
	<div id="player" class="6u 12u(narrower)" ></div>
	</div>
	<div class="row">
	<?php 
	$wrapperLastFm = new WrapperLastFm ( $artist, $song );
		$infoSong = $wrapperLastFm->getInfoSong ();
		echo $infoSong;
		?>
	<div class="6u 12u(narrower)" id="detAlbum"></div>
	</div>
	</section>
	</article>
	<!-- Footer -->
			<footer id="footer">

				<ul class="icons">
					<li><a href="https://www.facebook.com/giuseppe.santaniello" class="icon circle fa-facebook"> S</a></li><li>
					<li><a href="https://www.facebook.com/giuseppe.pietravalle" class="icon circle fa-facebook"> P</a></li>
					<li><a href="https://www.facebook.com/marco.mannara.9" class="icon circle fa-facebook"> M</a></li>
				</ul>

				<ul class="copyright">
					<li>&copy; G2M</li><li>Design: G2M</li>
				</ul>

			</footer>
</body>
</html>
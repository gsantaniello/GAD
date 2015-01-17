<html>
<head>
<meta charset="utf-8" />
<title>Music G2M</title>


<script
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
//		alert(response);
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
				
			$image="<img  src=". $source." height=\"300\" width=\"300\"></img>";
			$_SESSION[$artist]=$image;
		}
		
		echo "<div id='immagine'> " . $image.  "</div>";
		
		$wrapperMusixMatch = new WrapperMusixMatch ( $artist, $song, '' );
		$textSong = $wrapperMusixMatch->scrapingText ();
		if ($textSong == "0") {
			echo "<h1>Testo non trovato</h1>";
		} else {
			
			echo "<div id='testo' style='border:solid red 1px'> <pre>" . $textSong . "</pre></div>";
			
			$wrapperLastFm = new WrapperLastFm ( $artist, $song );
			$infoSong = $wrapperLastFm->getInfoSong ();
			echo $infoSong;
			
			$request = $artist . " " . $song;
			$wrapperYouTube = new WrapperYouTube ( $request );
			$idVideo = $wrapperYouTube->getIdByName ();
			echo "<p id='idVideo' hidden>" . $idVideo . "</p>";
		}
		?>
		
	<div id="detAlbum"></div>
<div id="player"></div>

</html>
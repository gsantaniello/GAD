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
					'onReady' : onPlayerReady,
					'onStateChange' : onPlayerStateChange
				}
			});
		}
		
		// 4. The API will call this function when the video player is ready.
		function onPlayerReady(event) {
			event.target.playVideo();
		}
	
		// 5. The API calls this function when the player's state changes.
		//    The function indicates that when playing a video (state=1),
		//    the player should play for six seconds and then stop.
		var done = false;
		function onPlayerStateChange(event) {
	
		}
		function stopVideo() {
			player.stopVideo();
		}
	</script>
</head>
		<?php  
		include("WrapperMusixMatch.php");
		include("WrapperYouTube.php");
		include("WrapperLastFm.php");
		$artist=$_GET['artist'];
		$song=$_GET['song'];
		
		$wrapperMusixMatch = new WrapperMusixMatch($artist, $song);
		$wrapperLastFm = new WrapperLastFm($artist, $song);

// 		if($artist == null && $song != null){
// 			//$listSongs = $wrapperLastFm->getListSongs();
// 			$listSongs = $wrapperMusixMatch->scrapingListSongs();
			
// 			echo $listSongs;
// 		}
		
// 		if($artist != null && $song == null){
// 			//$listSongs = $wrapperLastFm->getListSongs();
// 			$list = $wrapperMusixMatch->scrapingArtistSongs();
				
// 			echo $list;
// 		}
				
		if($artist != null && $song != null){
			$textSong = $wrapperMusixMatch->scrapingText();
			if($textSong=="0"){
				echo "<h1>Testo non trovato</h1>";
				echo "<h3>Controllare i dati in input ^_^</h3>";
			}
			else{
				echo "<div id='testo'> <pre>" . $textSong . "</pre></div>";
				$infoSong = $wrapperLastFm->getInfoSong ();
				echo $infoSong;
				
				$request = $artist . " " . $song;
				$wrapperYouTube = new WrapperYouTube ( $request );
				$idVideo = $wrapperYouTube->getIdByName ();
				echo "<p id='idVideo' hidden>" . $idVideo . "</p>";
			}
		}
		
		
		
		?>

	<div id="player"></div>

</html>
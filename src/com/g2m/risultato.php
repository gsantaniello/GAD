<html>
		<title>Music G2M</title>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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

		<?php  
		include("WrapperMusixMatch.php");
		include("WrapperYouTube.php");
		include("WrapperLastFm.php");
		$artist=$_POST['artist'];
		$song=$_POST['song'];
		
		$wrapperMusixMatch = new WrapperMusixMatch($artist, $song);
		$textSong = $wrapperMusixMatch->scrapingText();
		
		echo "<div id='testo'> <pre>".htmlspecialchars_decode($textSong)."</pre></div>";
		
		$wrapperLastFm = new WrapperLastFm($artist, $song);
		$infoSong = $wrapperLastFm->scrapingInfoSong();
		
		echo $infoSong;
		
		$request = $artist." ".$song;
		$wrapperYouTube = new WrapperYouTube($request);
		$idVideo = $wrapperYouTube->getIdByName();
		echo "<p id='idVideo' hidden>".$idVideo."</p>"
		
		?>

	<div id="player"></div>

</html>
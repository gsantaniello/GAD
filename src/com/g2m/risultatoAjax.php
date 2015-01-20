		<?php
		session_start();
		include ("WrapperWikia.php");
		include ("WrapperMusixMatch.php");
		include ("WrapperLastFm.php");
		include("WrapperGoogleImage.php");
		
		
		if (isset($_GET ['artist'])){
			$artist =$_GET['artist'];
			if(isset($_SESSION[$artist])){
				$image=$_SESSION[$artist];
			}
			else
			{
				$wrapperGoogleImage = new WrapperGoogleImage ( $artist );
				$json = $wrapperGoogleImage->get_url_contents ();
				
				$data = json_decode ( $json );
				
				$source=$data->responseData->results [0]->url;
				
				$image="<img src=". $source."></img>";
				$_SESSION[$artist]=$image;
			}
			
		}
		else {
			$artist=null;
		}
		
		if (isset($_GET ['song'])){
			$song =$_GET['song'];
		}
		else {
			$song=null;
		}
		
		if (isset($_GET ['album'])){
			$album =$_GET['album'];
		}
		else {
			$album=null;
		}
		
		
		
		
		// canzone inserita
		if ($artist == null && $song != null && $album==null) {
			$wrapperMusixMatch = new WrapperMusixMatch ( $artist, $song, " ");
			$listSongs = $wrapperMusixMatch->scrapingListSongs ();
			echo $listSongs;
		}
		
		// artista inserito
		if ($artist != null && $song == null && $album==null) {
			$wrapperMusixMatch = new WrapperMusixMatch ( $artist, $song, " ");
			$list = $wrapperMusixMatch->scrapingArtistSongs ();
			$wrapperWikia = new wrapperWikia ( $artist );
			
			$albums = $wrapperWikia->scrapingAlbum ();
			
			echo "
					<span class='artista'>$artist</span>
					<div class='image' id='immagine'>".$image."</div>
					<div class='row'>
					<div class='6u 12u(narrower)' id='album' > " . $albums . "</div>
				<div class='6u 12u(narrower)' id='popularSong' >" . $list . "</div></div>";
		}
		
		
		if ($artist != null && $album!=null && $song==null){
			$wrapperLastFm = new WrapperLastFm($artist, " ");
			$infoSong = $wrapperLastFm->getInfoAlbum($album);
			echo "<p class='canzone'>".$album."<p/>".$infoSong;	
		}
		
		?>
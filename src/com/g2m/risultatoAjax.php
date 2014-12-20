		<?php
		include ("WrapperWikia.php");
		include ("WrapperMusixMatch.php");
		include ("WrapperLastFm.php");
		
		if (isset($_GET ['artist'])){
			$artist =$_GET['artist'];
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
			
			echo "<div id='album' style='border:1px solid red'> <pre>" . $albums . "</pre></div>
				<div id='popularSong' style='border:1px solid green'>" . $list . "</div>";
		}
		
		if ($artist != null && $album!=null && $song==null){
			$wrapperLastFm = new WrapperLastFm($artist, " ");
			$infoSong = $wrapperLastFm->getInfoAlbum($album);
			echo $infoSong;	
		}
		
		?>
<?php
include ("WrapperWikia.php");
include ("WrapperMusixMatch.php");

$artist = $_GET ['artist'];
$song = $_GET ['song'];

$wrapperMusixMatch = new WrapperMusixMatch ( $artist, $song, " ");

// canzone inserita
if ($artist == null && $song != null) {
	$listSongs = $wrapperMusixMatch->scrapingListSongs ();
	echo $listSongs;
}

// artista inserito
if ($artist != null && $song == null) {
	$list = $wrapperMusixMatch->scrapingArtistSongs ();
	$wrapperWikia = new wrapperWikia ( $artist );
	
	$albums = $wrapperWikia->scrapingAlbum ();
	
	echo "<div id='album' style='border:1px solid red'> <pre>" . $albums . "</pre></div>
		<div id='popularSong' style='border:1px solid green'>" . $list . "</div>";
}

?>
<html>
<head>
<meta charset="utf-8"/>
<title>Music G2M</title>

</head>
<?php  
include("WrapperMusixMatch.php");
include("WrapperLastFm.php");
$artist=$_GET['artist'];
$song=$_GET['song'];

$wrapperMusixMatch = new WrapperMusixMatch($artist, $song);
$wrapperLastFm = new WrapperLastFm($artist, $song);


if($artist == null && $song != null){
	$listSongs = $wrapperLastFm->getListSongs();
	
	echo $listSongs;
}

if($artist != null && $song != null){
	$textSong = $wrapperMusixMatch->scrapingText();
	
	echo "<div id='testo'> <pre>".$textSong."</pre></div>";
	
	$infoSong = $wrapperLastFm->getInfoSong();
	
	echo $infoSong;
}
	
?>
<html>
<head>
<title>Music G2M</title>
</head>
<?php  
include("WrapperMusixMatch.php");
include("WrapperLastFm.php");
$artist=$_POST['artist'];
$song=$_POST['song'];

$wrapperMusixMatch = new WrapperMusixMatch($artist, $song);
$textSong = $wrapperMusixMatch->scrapingText();

echo "<div id='testo'> <pre>".htmlspecialchars_decode($textSong)."</pre></div>";

$wrapperLastFm = new WrapperLastFm($artist, $song);
$infoSong = $wrapperLastFm->scrapingInfoSong();

echo $infoSong;
	
?>
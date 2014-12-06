<html>
<head>
<title>Music G2M</title>
</head>
<?php  
//per formattare il artista e canzone sia per url musixmatch sia lastFm
function format($array){
	$size = sizeof($array);
	$result="";
	$musix="";
	$last="";
	for ($i=0;$i<$size-1;$i++){
		$musix .= $array[$i]."-";
		$last .= $array[$i]."%20";
	}
	
	$musix .= $array[$size-1];
	$last .= $array[$size-1];
	$result[0] = $musix;
	$result[1] = $last;
	
	return $result;
}


$artist=$_POST['artist'];
$song=$_POST['song'];

$arrayArtist = explode(' ',$artist);
$arrayTitle = explode(' ',$song);

$sizeArtist = sizeof($arrayArtist);
$sizeTitle = sizeof($arrayTitle);

if($sizeArtist>1){
	$formatString = format($arrayArtist);
	$author = $formatString[0]; 
	$authorLast = $formatString[1];
}
else {
	$author = $arrayArtist[0];
	$authorLast = $arrayArtist[0];
}

if($sizeTitle>1){
	$formatString = format($arrayTitle);
	$title =  $formatString[0];
	$titleLast =  $formatString[1];
}
else {
	$title = $arrayTitle[0];
	$titleLast = $arrayTitle[0];
}


$uriMusix = 'https://www.musixmatch.com/lyrics/'.$author."/".$title;
$uriLastfm = 'http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=6a53efea876de1447612a9f7f65ce432&artist='.$authorLast.'&track='.$titleLast;
//$uriLastfm='http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=6a53efea876de1447612a9f7f65ce432&artist=Ligabue&track=certe%20notti'; //formattare il titolo della canzone e l'artista con %20
$getMusix = file_get_contents($uriMusix);
$getLastfm = file_get_contents($uriLastfm);

$dom = new DOMDocument();
@$dom->loadHTML($getMusix);
$query = "//div[@id='lyrics']";
$xpath = new DOMXPath($dom);
$result = $xpath->query($query);

$dom2 = new DOMDocument();
@$dom2->loadXML($getLastfm);
$query2 = "//album/title";
$query3 = "//album/image[@size='medium']";
$query4 = "//track/duration";
$xpath2 = new DOMXPath($dom2);
$result2 = $xpath2->query($query2); //titolo album
$result3 = $xpath2->query($query3); //img album
$result4 = $xpath2->query($query4);
$cover = $result3->item(0)->nodeValue; //img album
$str = $result->item(0)->nodeValue;
//$t = mb_convert_encoding($str, "UTF-8");

//print_r($result4->item(0)->nodeValue);
$duration = $result4->item(0)->nodeValue/60; //prendere la prima cifra, inserire il punto e eliminare lo zero

echo "<div id='testo'> <pre>".$str."</pre></div>
		<div id='info'> 
		<table border='1'>
			<th>Info</th>
			<tr><td>Durata</td><td>".$duration."</td></tr>	
			<tr><td>Album</td><td>".$result2->item(0)->nodeValue."</td></tr>
			<tr><td>Copertina</td><td><img src=$cover></td></tr>
		</table>
	  </div>";

?>
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


// $artist=$_POST['artist'];
// $song=$_POST['song'];

// $arrayArtist = explode(' ',$artist);
// $arrayTitle = explode(' ',$song);

// $sizeArtist = sizeof($arrayArtist);
// $sizeTitle = sizeof($arrayTitle);

// if($sizeArtist>1){
// 	$formatString = format($arrayArtist);
// 	$author = $formatString[0]; 
// 	$authorLast = $formatString[1];
// }
// else {
// 	$author = $arrayArtist[0];
// 	$authorLast = $arrayArtist[0];
// }

// if($sizeTitle>1){
// 	$formatString = format($arrayTitle);
// 	$title =  $formatString[0];
// 	$titleLast =  $formatString[1];
// }
// else {
// 	$title = $arrayTitle[0];
// 	$titleLast = $arrayTitle[0];
// }

$uriMusix = "https://www.musixmatch.com/it/search/jovanotti";
//$uriMusix = 'https://www.musixmatch.com/lyrics/'.$author."/".$title;
//$uriLastfm = 'http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=6a53efea876de1447612a9f7f65ce432&artist='.$authorLast.'&track='.$titleLast;
//$uriLastfm='http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=6a53efea876de1447612a9f7f65ce432&artist=Ligabue&track=certe%20notti'; //formattare il titolo della canzone e l'artista con %20
$getMusix = file_get_contents($uriMusix);
//$getLastfm = file_get_contents($uriLastfm);

$dom = new DOMDocument();
@$dom->loadHTML($getMusix);
//$query = "//ul[@class='tracks list']//a/@href";
$query = "//ul[@class='tracks list']//h2/a";
$xpath = new DOMXPath($dom);
$result = $xpath->query($query);


print_r($result);
echo "<pre>";
//print_r($result->item(1)->attributes->item(0)->value);
$output = "<ul>";
$len = $result->length;
for($i=0; $i<$len;$i++){
	$href = "https://www.musixmatch.com".$result->item($i)->attributes->item(0)->value;
	$output .= "<li><a href=' ".$href."'>".$result->item($i)->nodeValue."</li>";
}
$output .= "</ul>";
echo $output;
?>
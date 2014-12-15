<?php
class WrapperLastFm {
	const URI = 'http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=';
	const KEY = '6a53efea876de1447612a9f7f65ce432';
	// $uriLastfm = 'http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=6a53efea876de1447612a9f7f65ce432&artist='.$authorLast.'&track='.$titleLast;
	private $artist;
	private $song;
	private $url;
	public function __construct($artist, $song) {
		$this->artist = $artist;
		$this->song = $song;
		
		$this->verifyInput ();
	}
	private function verifyInput() {
		$arrayArtist = explode ( ' ', $this->artist );
		$arrayTitle = explode ( ' ', $this->song );
		
		$sizeArtist = sizeof ( $arrayArtist );
		$sizeTitle = sizeof ( $arrayTitle );
		
		if ($sizeArtist > 1) {
			$this->artist = $this->format ( $arrayArtist );
		} else {
			$this->artist = $arrayArtist [0];
		}
		
		if ($sizeTitle > 1) {
			$this->song = $this->format ( $arrayTitle );
		} else {
			$this->song = $arrayTitle [0];
		}
	}
	private function format($array) {
		$size = sizeof ( $array );
		$result = "";
		for($i = 0; $i < $size - 1; $i ++) {
			$result .= $array [$i] . "%20";
		}
		$result .= $array [$size - 1];
		return $result;
	}
	public function scrapingInfoSong() {
		$this->url = self::URI . self::KEY . "&artist=" . $this->artist . "&track=" . $this->song;
		//echo $this->url;
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
		);
		@$getLastFm = file_get_contents ($this->url,false, stream_context_create($arrContextOptions));
		if ($getLastFm != false) {
			$dom = new DOMDocument();
			@$dom->loadXML($getLastFm);
			$xpath = new DOMXPath ( $dom );
			
			$query2 = "//album/title";
			$query3 = "//album/image[@size='medium']";
			$query4 = "//track/duration";
			
			$result2 = $xpath->query ( $query2 ); // titolo album
			$result3 = $xpath->query ( $query3 ); // img album
			$result4 = $xpath->query ( $query4 );
			//print_r($result2);
			$albumName = $result2->item ( 0 )->nodeValue;
			$cover = $result3->item ( 0 )->nodeValue; // img album
			$d = $result4->item ( 0 )->nodeValue / 60; // prendere la prima cifra, inserire il punto e eliminare lo zero

			$duration = $this->formatDurata($d);
			
		} 

		$resultScraping = "<div id='info'> 
		<table border='1'>
			<th colspan='2' style='text-align:center;'>Info</th>
			<tr><td>Album</td><td>" . $albumName . "</td></tr>
			<tr><td>Copertina</td><td><img id='cover' src=$cover></td></tr>
			<tr><td>Durata</td><td>" . $duration . "</td></tr>	
			
		</table>
	  </div>";
		
		return $resultScraping;
	}
	
	private function formatDurata($string){
		$start=substr($string, 0,1);
		$end = substr($string, 1,(strlen($string)-2));
		return $start.".".$end." min";
	}
	
	public function getArtist() {
		return $this->artist;
	}
	public function getSong() {
		return $this->song;
	}
	public function getUrl() {
		return $this->url;
	}
}

?>
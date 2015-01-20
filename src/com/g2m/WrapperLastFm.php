<?php
class WrapperLastFm {
	const URI = 'http://ws.audioscrobbler.com/2.0/?method=';
	const KEY = '6a53efea876de1447612a9f7f65ce432';
	private $artist;
	private $song;
	private $url;
	
	public function __construct($artist, $song) {
		$this->artist = $artist;
		$this->song = $song;
		$this->verifyInput ();
	}
	
	private function verifyInput() {
		$arrayArtist = split("[-]", $this->artist);
		$arrayTitle =  split("[-]", $this->song);
		//print_r($arrayTitle);
		
		$artist = implode(" ",$arrayArtist);
		$song = implode(" ",$arrayTitle);
		
		$this->artist = rawurlencode($artist);
		$this->song = rawurlencode($song);
						
	}
	
	private function getContextOptions(){
		$arrContextOptions = array (
				"ssl" => array (
						"verify_peer" => false,
						"verify_peer_name" => false
				),
				'http'=>array(
						'method'=>"GET",
						'header'=>"Accept-language: en\r\n" .
						"Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
						"User-Agent: Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/124 (KHTML, like Gecko) Safari/125\r\n"
				)
		);
		return $arrContextOptions;
	}
		
	public function getInfoAlbum($album){
		$a = rawurlencode($album);
		$this->url = self::URI ."album.getinfo&api_key=". self::KEY . "&artist=" . $this->artist . "&album=" . $a;
		//echo $this->url;
		$albumName=null;
		$cover=null;
		$duration=null;
		
		$context = $this->getContextOptions();
		@$getLastFm = file_get_contents ( $this->url, false, stream_context_create ( $context ) );
		if ($getLastFm != false) {
			$dom = new DOMDocument();
			@$dom->loadXML($getLastFm);
			$xpath = new DOMXPath ( $dom );
			$query = "//album/tracks/track/name";
				
			$result = $xpath->query ( $query ); // titoli tracks
			//print_r($result);
			$n=$result->length;
			$resultScraping = "<div id='infoAlbum'><ul>";
			
			for($i=0;$i<$n;$i++){
				$href = "risultato.php?artist=".$this->artist."&song=".$result->item ($i)->nodeValue;
				$resultScraping.="<li><a href=\" " . $href . "\" >" .$result->item($i)->nodeValue."</a></li>";
			}
			$resultScraping.="</ul></div>";
			return $resultScraping;
		}
	}
	
	public function getInfoSong() {
		$this->url = self::URI ."track.getInfo&api_key=". self::KEY . "&artist=" . $this->artist . "&track=" . $this->song;
		//echo $this->url;
		$albumName=null;
		$cover=null;
		$duration=null;
		
		$context = $this->getContextOptions();
		@$getLastFm = file_get_contents ( $this->url, false, stream_context_create ( $context ) );
		
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
			@$albumName = $result2->item ( 0 )->nodeValue; //inserire @ per eliminare warning
			@$cover = $result3->item ( 0 )->nodeValue; // img album
			
			$d = ($result4->item ( 0 )->nodeValue)/1000; // prendere la prima cifra, inserire il punto e eliminare lo zero
			$duration  = gmdate("i:s",$d);
			//$duration = $this->formatDurata($d);
			
		} 
		$resultScraping = "<div class='6u 12u(narrower)' id='info'>";
		
		if(!($albumName==null && $cover==null && $duration==null)){
			$resultScraping.="<table>
			<th colspan='2' style='text-align:center;'><strong>Info<strong></th>
			<tr><td><strong>Album</strong></td><td><strong>" . $albumName . "</strong></td></tr>
			<tr><td><strong>Copertina</storng></td><td><a href=\"#detAlbum\" ><img id=\"cover\" onclick=\"dettaglioAlbum('$this->artist','$albumName')\" src=". $cover."></a></td></tr>
			<tr><td><strong>Durata</strong></td><td><strong>" . $duration . " min</strong></td></tr>	
			</table>";	
		}
		$resultScraping.="</div>";
		
		
		return $resultScraping;
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
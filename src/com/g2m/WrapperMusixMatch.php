<?php
class WrapperMusixMatch {
	const URI = 'https://www.musixmatch.com/lyrics/';
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
			$result .= $array [$i] . "-";
		}
		$result .= $array [$size - 1];
		return $result;
	}
	public function scrapingText() {
		$this->url = self::URI . $this->artist . "/" . $this->song;
		// echo $this->url;
		@$getMusix = file_get_contents ( $this->url );
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$query = "//div[@id='lyrics']";
			$xpath = new DOMXPath ( $dom );
			$result = $xpath->query ( $query );
			// print_r($result);
			$textSong = $result->item ( 0 )->nodeValue;
		} 

		else {
			$textSong = "Testo non trovato";
		}
		
		return $textSong;
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
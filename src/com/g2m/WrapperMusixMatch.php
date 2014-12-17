<?php
class WrapperMusixMatch {
	const URI = 'https://www.musixmatch.com/';
	private $artist;
	private $song;
	private $url;
	public function __construct($artist, $song) {
		$this->artist = $artist;
		$this->song = $song;
		
		$this->verifyInput ();
	}
	private function verifyInput() {
		$arrayArtist = split("['|' '|_]", $this->artist );
		echo"<pre>";
		print_r($arrayArtist);
		$arrayTitle =  split("['|' '|_]", $this->song);
		echo"<pre>";
		print_r($arrayTitle);
		
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
		echo $result;
		
		return $result;
	}
	public function scrapingText() {
		$this->url = self::URI . "lyrics/" . $this->artist . "/" . $this->song;
		// echo $this->url;
		$arrContextOptions = array (
				"ssl" => array (
						"verify_peer" => false,
						"verify_peer_name" => false 
				) 
		);
		@$getMusix = file_get_contents ( $this->url, false, stream_context_create ( $arrContextOptions ) );
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$query = "//div[@id='lyrics']";
			$xpath = new DOMXPath ( $dom );
			$result = $xpath->query ( $query );
			// echo "<pre>";
			// print_r($result);
			$textSong = $result->item ( 0 )->nodeValue;
		} 

		else {
			$textSong = "0";
		}
		
		return $textSong;
	}
	public function scrapingListSongs() {
		$this->url = self::URI . "search/" . $this->song;
		$arrContextOptions = array (
				"ssl" => array (
						"verify_peer" => false,
						"verify_peer_name" => false 
				) 
		);
		@$getMusix = file_get_contents ( $this->url, false, stream_context_create ( $arrContextOptions ) );
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$xpath = new DOMXPath ( $dom );
			
			$query = "//ul[@class='tracks list']//h2/a";
			$query2 = "//ul[@class='tracks list']//h3//a";
			
			$result = $xpath->query ( $query );
			$result2 = $xpath->query ( $query2 );
			
			//print_r ( $result2);
			echo "<pre>";
			//print_r($result->item(1)->attributes->item(0)->value);
			$list = "<ul>";
			$len = $result->length;
			for($i = 0; $i < $len; $i ++) {
				
				$this->song = $result->item ( $i )->nodeValue;
				$this->artist = $result2->item($i)->nodeValue;
				$href = "risultato.php?artist=".$this->artist."&song=".$this->song;
				$list .= "<li><a href=' ".$href." '>".$this->song ." - ".$this->artist. "</a></li>";
			}	
			$list .= "</ul>";
			return $list;
		}
	}
	public function scrapingArtistSongs() {
		$this->url = self::URI . "it/testi/" . $this->artist;
		$arrContextOptions = array (
				"ssl" => array (
						"verify_peer" => false,
						"verify_peer_name" => false
				)
		);
		@$getMusix = file_get_contents ( $this->url, false, stream_context_create ( $arrContextOptions ) );
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$xpath = new DOMXPath ( $dom );
				
			$query = "//ul[@class='tracks list']//h2/a";
			$query2 = "//ul[@class='tracks list']//h3//a";
				
			$result = $xpath->query ( $query );
			$result2 = $xpath->query ( $query2 );
				
			//print_r ( $result2);
			echo "<pre>";
			//print_r($result->item(1)->attributes->item(0)->value);
			$list = "<ul>";
			$len = $result->length;
			for($i = 0; $i < $len; $i ++) {
	
				$this->song = $result->item ( $i )->nodeValue;
				$this->artist = $result2->item($i)->nodeValue;
				//format($this->song); //controllare canzone con titolo che contiene apostrofo
				$href = "risultato.php?artist=".$this->artist."&song=".$this->song;
				$list .= "<li><a href=' ".$href." '>".$this->song ." - ".$this->artist. "</a></li>";
			}
			$list .= "</ul>";
			return $list;
		}
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
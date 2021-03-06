<?php
class WrapperMusixMatch {
	const URI = 'https://www.musixmatch.com/';
	private $artist;
	private $song;
	private $title;
	private $url;
	public function __construct($artist, $song, $url) {
		$this->artist = $artist;
		$this->song = $song;
		$this->title= $song;
		$this->url = $url;
		//echo "Echo musixmatch costruttore: ".$this->artist." and ". $this->song;
		$this->verifyInput ();
	}
	private function verifyInput() {
		$arrayArtist = split ( "[ ]", $this->artist );
// 		echo "<pre>";
// 		print_r ( $arrayArtist );
// 		echo "Echo musixmatch costruttore: ". $this->song;
		$arrayTitle = split ( "[ ]", $this->song );
// 		echo "<pre>";
// 		print_r ( $arrayTitle );
		
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
		//echo $result;
		
		return $result;
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
	public function scrapingText() {
		if (isset ( $_GET ['url'] )) {
			$this->url = self::URI . $_GET ['url'];
		} else {
			$this->url = self::URI . "it/testo/" . $this->artist . "/" . $this->song;
		}
		
		// echo "url".$this->url;
		 $context = $this->getContextOptions();
		
		@$getMusix = file_get_contents ( $this->url, false, stream_context_create ( $context ) );
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$xpath = new DOMXPath ( $dom );
 			$result = $xpath->query ("//div[@id='lyrics']");
// 			echo "<pre>";
// 			print_r($result);
			$textSong = $result->item (0)->nodeValue;
		} 
		
		else {
			$textSong = "0";
		}
		
		return $textSong;
	}
	
	// metodo richiamato quando si cerca solo per canzone nella pagina home.html
	public function scrapingListSongs() {
		$this->url = self::URI . "search/" . $this->song;
		$context = $this->getContextOptions();
		@$getMusix = file_get_contents ( $this->url, false, stream_context_create ( $context ) );
		
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$xpath = new DOMXPath ( $dom );
			$query = "//ul[@class='tracks list']//h2/a";
			$query2 = "//ul[@class='tracks list']//h3";
			$result = $xpath->query ( $query );
			$result2 = $xpath->query ( $query2 );
				
			// print_r ( $result2);
			// echo "<pre>";
			// print_r($result->item(1)->attributes->item(0)->value);
			$list = "<ul>";
			$len = $result->length;
				
			for($i = 0; $i < $len; $i ++) {
				$titolo = strtoupper ($this->title);
				$cerco = strtoupper ($result->item ($i)->nodeValue);
				if (strpos($cerco,$titolo) !== false) {
					$this->song = $result->item ( $i )->nodeValue;
					$this->artist = $result2->item ( $i )->nodeValue;
					$valueUrl = $result->item ( $i )->attributes->item ( 0 )->value;
					//$href = "risultato.php?url=" . $valueUrl;
					$href = "risultato.php?artist=".$this->artist."&song=".$this->song;
					//$list .= "<li><a href=\" " . $href . "\" >" . $songItem."</a></li>";
					$list .= "<li><a href=\" " . $href . "\" >" . $this->song . " - " . $this->artist . "</a></li>";
				}
			}
			$list .= "</ul>";
			return $list;
		}	
	}
	
	//metodo richiamato quando si inserisce solo l'artista in home.html 
	public function scrapingArtistSongs() {
		$this->url = self::URI . "it/testi/" . $this->artist;
		$context = $this->getContextOptions();
		@$getMusix = file_get_contents ( $this->url, false, stream_context_create ( $context ) );
		
		if ($getMusix != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getMusix );
			$xpath = new DOMXPath ( $dom );
			$query = "//ul[@class='tracks list']//h2/a";
			$query2 = "//ul[@class='tracks list']//h3";
			$result = $xpath->query ( $query );
			$result2 = $xpath->query ( $query2 );
			
			// print_r ( $result2);
			// echo "<pre>";
			// print_r($result->item(1)->attributes->item(0)->value);
			$list="<span class=\"liste\">Popular Songs</span><ul>";
			$len = $result->length;
			
			for($i = 0; $i < $len; $i ++) {
				
				$this->song = $result->item ( $i )->nodeValue;
				$this->artist = $result2->item ( $i )->nodeValue;
				$valueUrl = $result->item ( $i )->attributes->item ( 0 )->value;
				//$href = "risultato.php?url=" . $valueUrl;
				$href = "risultato.php?artist=".$this->artist."&song=".$this->song;
				//$list .= "<li><a href=\" " . $href . "\" >" . $songItem."</a></li>";
				$list .= "<li><a href=\" " . $href . "\" >" . $this->song . " - " . $this->artist . "</a></li>";
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
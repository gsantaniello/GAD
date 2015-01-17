<?php
class WrapperWikia {
	const URI = 'http://lyrics.wikia.com/api.php?artist=';
	const FORMATO='&fmt=xml';
	private $artist;
	private $url;
	
	
	public function __construct($artist) {
		$this->artist = rawurlencode($artist);
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
	
 	public function scrapingAlbum() {
		$this->url = self::URI . $this->artist . self::FORMATO;
		//echo $this->url;
				
		$context = $this->getContextOptions();
		@$getAlbum = file_get_contents ( $this->url, false, stream_context_create ( $context ) );
		
		if ($getAlbum != false) {
			$dom = new DOMDocument ();
			@$dom->loadHTML ( $getAlbum );
			$query1 = "//albums//album";
			$query2="//albums//songs";
			$query3 = "//albums//year";
				
			$xpath = new DOMXPath ( $dom );
			$result1= $xpath->query($query1);
			$result2= $xpath->query($query2);
			$result3= $xpath->query($query3);
				
			$len=$result1->length;
			$list="<h2>Lista ordinata per anno</h2><ul>";
			for ($i=0;$i<$len;$i++){
				$albumTitle=$result1->item($i)->nodeValue;
				$year = $result3->item($i)->nodeValue;
				$list.="<li>".$albumTitle." - Anno: ".$year."</li><ol>";
				$lenAlbum=$result2->item($i)->childNodes->length;
				
				for($j=0;$j<$lenAlbum;$j++)
				{
					$songItem=$result2->item($i)->childNodes->item($j)->nodeValue;
					$href = "risultato.php?artist=".$this->artist."&song=".$songItem;
					$list .= "<li><a href=\" " . $href . "\" >" . $songItem."</a></li>";
				}
				$list.="</ol>";
			}
			$list.="</ul>";
				
		}
		return $list;	
	
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
<?php
class WrapperWikia {
	const URI = 'http://lyrics.wikia.com/api.php?artist=';
	const FORMATO='&fmt=xml';
	private $artist;
	private $url;
	
	
	public function __construct($artist) {
		$this->artist = rawurlencode($artist);
	}
	
 	public function scrapingAlbum() {
		$this->url = self::URI . $this->artist . self::FORMATO;
		echo $this->url;
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
		);
		
		@$getAlbum= file_get_contents($this->url,false,stream_context_create($arrContextOptions));
		
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
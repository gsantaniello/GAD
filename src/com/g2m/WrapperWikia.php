<?php
class WrapperWikia {
	const URI = 'http://lyrics.wikia.com/api.php?artist=';
	const FORMATO='&fmt=xml';
	private $artist;
	private $url;
	
	
	public function __construct($artist) {
		$this->artist = $artist;
		$this->verifyInput ();
	}
	private function verifyInput() {
		$arrayArtist = explode ( ' ', $this->artist );
		
		$sizeArtist = sizeof ( $arrayArtist );
		
		if ($sizeArtist > 1) {
			$this->artist = $this->format ( $arrayArtist );
		} else {
			$this->artist = $arrayArtist [0];
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
			$xpath = new DOMXPath ( $dom );
			$result1 = $xpath->query ( $query1 );
			$result2= $xpath->query($query2);
			$len=$result1->length;
			$list="<ul>";
			for ($i=0;$i<$len;$i++){
				$albumTitle=$result1->item($i)->nodeValue;
				$list.="<li>".$albumTitle."</li><ol>";
				$lenAlbum=$result2->item($i)->childNodes->length;
				for($j=0;$j<$lenAlbum;$j++)
				{
					$songItem=$result2->item($i)->childNodes->item($j)->nodeValue;
					$list.="<li>".$songItem."</li>";
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
<?php
class WrapperGoogleImage {
	const URI ='http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=';
	private $request;
	
	
	public function __construct($request) {
		//salvo il nome dell'artista e della canzone all'interno della variabile request
		$this->artist= rawurlencode($request);
		$this->url = self::URI .$this->artist;	
	}
	
	
	
	
	function get_url_contents() {
    $crl = curl_init();

    curl_setopt($crl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($crl, CURLOPT_URL,$this->url );
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);

    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}


	

}
?>
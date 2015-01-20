<?php
class WrapperYouTube {

	private $request;
	private $DEVELOPER_KEY;
	private $client;
	
	public function __construct($request) {
		//salvo il nome dell'artista e della canzone all'interno della variabile request
		$this->request = $request;
		
		set_include_path("./google-api-php-client/src");
		require_once 'Google_Client.php';
		require_once 'contrib/Google_YouTubeService.php';
		/* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
		 Google APIs Console <http://code.google.com/apis/console#access>
		 Please ensure that you have enabled the YouTube Data API for your project. */
		$this->DEVELOPER_KEY = 'AIzaSyDSYLzQtat7HuHc0KkQTnxrZWFvMkMxujk';
		$this->client = new Google_Client();
	}
	
	public function getIdByName(){
		$this->client->setDeveloperKey($this->DEVELOPER_KEY);
		$youtube = new Google_YoutubeService($this->client);
		@$searchResponse = $youtube->search->listSearch('id,snippet', array('q' => $this->request,))['items'][0]['id']['videoId'];
		
		return $searchResponse; 
	}

}
?>
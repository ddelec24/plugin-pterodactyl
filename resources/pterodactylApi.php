<?php

class pterodactylApi {
	
	private $apiKey;
	private $rootUrl;
  	private $isAdmin;
	
	public function __construct( $apiKey, $rootUrl, $isAdmin ) {
		$this->apiKey = $apiKey;
		$this->rootUrl = $rootUrl;
      	$this->isAdmin = $isAdmin;
	}
	
	private function sendRequest( $endpoint, $method = "GET", $args = "" ) {
		// https://dashflo.net/docs/api/pterodactyl/v1/#req_26cd9ef4a75540d6be8b4ef683e2b1a2
		$extras = ($method != "GET") ? " -d " . $args : "";
		$response = shell_exec('curl "' . $this->rootUrl . $endpoint . '" -H "Authorization: Bearer '. $this->apiKey .
                               '" -H "Content-Type: application/json" -H "Accept: Application/vnd.pterodactyl.v1+json" -X ' . $method . $extras);

		$jsonResponse = json_decode(utf8_encode($response));
		if (json_last_error() != JSON_ERROR_NONE)  //erreur dans le traitement du fichier json
      		return json_last_error_msg() . "détail retour: " . $response;
      
      	return $jsonResponse;
	}

	// ******************* ENDPOINT => CLIENT *******************
	public function getListServers() {    
      	return ($this->isAdmin) ? $this->sendRequest("/api/application/servers") : $this->sendRequest("/api/client");;
	}

	// ******************* ENDPOINT => CLIENT/SERVERS *******************
	public function getServerDetails($uid) {
		if(empty($uid))
			return false;

		$endpoint = "/api/client/servers/" . $uid;

		return $this->sendRequest($endpoint);
	}

	public function getResourcesUsage($uid) {
		if(empty($uid))
			return false;

		$endpoint = "/api/client/servers/" . $uid . "/resources";
		return $this->sendRequest($endpoint);
	}

	// ******************* ENDPOINT => CLIENT/SERVERS/NETWORKS *******************
	public function getListAllocations($uid) {
		if(empty($uid))
			return false;

		$endpoint = "/api/client/servers/" . $uid . "/network/allocations";
		return $this->sendRequest($endpoint);
	}
}



//print_r($srvDetails);

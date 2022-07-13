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
		if($method == "GET") {

			$request = 'curl "' . $this->rootUrl . $endpoint . '" -H "Authorization: Bearer '. $this->apiKey .
			'" -H "Content-Type: application/json" -H "Accept: Application/vnd.pterodactyl.v1+json" -X ' . $method;
          	log::add('pterodactyl', 'debug', $request);
			$response = shell_exec($request);
			$jsonResponse = json_decode(utf8_encode($response));

			if (json_last_error() != JSON_ERROR_NONE)  //erreur dans le traitement du fichier json
				return json_last_error_msg() . "détail retour: " . $response;

		} else {

			$request = 'curl "' . $this->rootUrl . $endpoint . '" -H "Authorization: Bearer '. $this->apiKey .
			'" -H "Content-Type: application/json" -H "Accept: application/json" -X ' . $method . ' -d \'' . $args . '\'';
			$response = shell_exec($request);
			$jsonResponse = $response; // @TODO retour http 204, donc contenu vide, à voir si on rajoute -i dans la requete pour vérifier qu'on a bien un 204
		}

			
			return $jsonResponse;
	}

	// ******************* ENDPOINT => CLIENT *******************
	public function getListServers() {    
		return ($this->isAdmin) ? $this->sendRequest("/api/application/servers") : $this->sendRequest("/api/client");
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

	public function getNewSocket($uid) {
		if(empty($uid))
			return false;

		$endpoint = "/api/client/servers/" . $uid . "/websocket";
		return $this->sendRequest($endpoint);
	}

	public function postChangeState($uid, $newState) {
		return $this->sendRequest('/api/client/servers/' . $uid . '/power', 'POST', '{"signal": "' . $newState . '"}');
	}
	// ******************* ENDPOINT => CLIENT/SERVERS/NETWORKS *******************
	public function getListAllocations($uid) {
		if(empty($uid))
			return false;

		$endpoint = "/api/client/servers/" . $uid . "/network/allocations";
		return $this->sendRequest($endpoint);
	}
}
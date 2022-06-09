<?php

class pterodactylApi {
	
	private $apiKey;
	private $rootUrl;
	
	public function __construct( $apiKey, $rootUrl ) {
		$this->apiKey = $apiKey;
		$this->rootUrl = $rootUrl;
	}
	
	private function sendRequest( $endpoint, $method = "GET", $args = "" ) {
		// https://dashflo.net/docs/api/pterodactyl/v1/#req_26cd9ef4a75540d6be8b4ef683e2b1a2
		$extras = ($method != "GET") ? " -d " . $args : "";
		$response = shell_exec('curl "' . $this->rootUrl . $endpoint . '" -H "Authorization: Bearer '. $this->apiKey .
                               '" -H "Content-Type: application/json" -H "Accept: Application/vnd.pterodactyl.v1+json" -X ' . $method . $extras);
      /*
		    // DEBUG
      if($endpoint == "/api/client") {
      $response = <<<'JSON'
{"object":"list","data":[{"object":"server","attributes":{"server_owner":true,"identifier":"281557b1","internal_id":6,"uuid":"281557b1-9cb3-46a1-8adc-9449d46cca24","name":"Adventure-Sky_lobby","node":"SRV-1","sftp_details":{"ip":"panel.illimity.fr","port":2025},"description":"","limits":{"memory":2048,"swap":0,"disk":25600,"io":500,"cpu":600,"threads":"0-5","oom_disabled":true},"invocation":"java -Xms128M -Xmx2048M -Dterminal.jline=false -Dterminal.ansi=true -jar server.jar","docker_image":"ghcr.io\/pterodactyl\/yolks:java_17","egg_features":["eula","java_version","pid_limit"],"feature_limits":{"databases":0,"allocations":0,"backups":5},"status":"suspended","is_suspended":true,"is_installing":false,"is_transferring":false,"relationships":{"allocations":{"object":"list","data":[{"object":"allocation","attributes":{"id":216,"ip":"192.168.1.201","ip_alias":"panel.illimity.fr","port":20110,"notes":null,"is_default":true}}]},"variables":{"object":"list","data":[{"object":"egg_variable","attributes":{"name":"Minecraft Version","description":"The version of minecraft to download. \r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.","env_variable":"MINECRAFT_VERSION","default_value":"latest","server_value":"latest","is_editable":true,"rules":"nullable|string|max:20"}},{"object":"egg_variable","attributes":{"name":"Server Jar File","description":"The name of the server jarfile to run the server with.","env_variable":"SERVER_JARFILE","default_value":"server.jar","server_value":"server.jar","is_editable":true,"rules":"required|regex:\/^([\\w\\d._-]+)(\\.jar)$\/"}},{"object":"egg_variable","attributes":{"name":"Build Number","description":"The build number for the paper release.\r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.","env_variable":"BUILD_NUMBER","default_value":"latest","server_value":"latest","is_editable":true,"rules":"required|string|max:20"}}]}}}},{"object":"server","attributes":{"server_owner":true,"identifier":"f65af35b","internal_id":8,"uuid":"f65af35b-086d-4fa7-b530-f7360fa09bf3","name":"PluginsTest1","node":"SRV-3","sftp_details":{"ip":"panel.illimity.fr","port":2024},"description":"","limits":{"memory":2048,"swap":0,"disk":1024,"io":500,"cpu":0,"threads":"0-3","oom_disabled":true},"invocation":"java -Xms128M -Xmx2048M -jar server.jar","docker_image":"ghcr.io\/pterodactyl\/yolks:java_17","egg_features":["eula","java_version","pid_limit"],"feature_limits":{"databases":0,"allocations":0,"backups":0},"status":null,"is_suspended":false,"is_installing":false,"is_transferring":false,"relationships":{"allocations":{"object":"list","data":[{"object":"allocation","attributes":{"id":7,"ip":"192.168.1.203","ip_alias":"panel.illimity.fr","port":20301,"notes":null,"is_default":true}}]},"variables":{"object":"list","data":[{"object":"egg_variable","attributes":{"name":"Minecraft Version","description":"The version of minecraft to download. \r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.","env_variable":"MINECRAFT_VERSION","default_value":"latest","server_value":"1.18.2","is_editable":true,"rules":"nullable|string|max:20"}},{"object":"egg_variable","attributes":{"name":"Server Jar File","description":"The name of the server jarfile to run the server with.","env_variable":"SERVER_JARFILE","default_value":"server.jar","server_value":"server.jar","is_editable":true,"rules":"required|regex:\/^([\\w\\d._-]+)(\\.jar)$\/"}},{"object":"egg_variable","attributes":{"name":"Build Number","description":"The build number for the paper release.\r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.","env_variable":"BUILD_NUMBER","default_value":"latest","server_value":"latest","is_editable":true,"rules":"required|string|max:20"}}]}}}}],"meta":{"pagination":{"total":2,"count":2,"per_page":50,"current_page":1,"total_pages":1,"links":{}}}}
JSON;
      } else {
        if(substr($endpoint, -9) != "resources") {
	$response = <<<'JSON'
{
	"object": "server",
	"attributes": {
		"server_owner": true,
		"identifier": "f65af35b",
		"internal_id": 8,
		"uuid": "f65af35b-086d-4fa7-b530-f7360fa09bf3",
		"name": "PluginsTest1",
		"node": "SRV-3",
		"sftp_details": {
			"ip": "panel.illimity.fr",
			"port": 2024
		},
		"description": "",
		"limits": {
			"memory": 2048,
			"swap": 0,
			"disk": 1024,
			"io": 500,
			"cpu": 0,
			"threads": "0-3",
			"oom_disabled": true
		},
		"invocation": "java -Xms128M -Xmx2048M -jar server.jar",
		"docker_image": "ghcr.io\/pterodactyl\/yolks:java_17",
		"egg_features": ["eula", "java_version", "pid_limit"],
		"feature_limits": {
			"databases": 0,
			"allocations": 0,
			"backups": 0
		},
		"status": null,
		"is_suspended": false,
		"is_installing": false,
		"is_transferring": false,
		"relationships": {
			"allocations": {
				"object": "list",
				"data": [{
					"object": "allocation",
					"attributes": {
						"id": 7,
						"ip": "192.168.1.203",
						"ip_alias": "panel.illimity.fr",
						"port": 20301,
						"notes": null,
						"is_default": true
					}
				}]
			},
			"variables": {
				"object": "list",
				"data": [{
					"object": "egg_variable",
					"attributes": {
						"name": "Minecraft Version",
						"description": "The version of minecraft to download. \r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.",
						"env_variable": "MINECRAFT_VERSION",
						"default_value": "latest",
						"server_value": "1.18.2",
						"is_editable": true,
						"rules": "nullable|string|max:20"
					}
				}, {
					"object": "egg_variable",
					"attributes": {
						"name": "Server Jar File",
						"description": "The name of the server jarfile to run the server with.",
						"env_variable": "SERVER_JARFILE",
						"default_value": "server.jar",
						"server_value": "server.jar",
						"is_editable": true,
						"rules": "required|regex:\/^([\\w\\d._-]+)(\\.jar)$\/"
					}
				}, {
					"object": "egg_variable",
					"attributes": {
						"name": "Build Number",
						"description": "The build number for the paper release.\r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.",
						"env_variable": "BUILD_NUMBER",
						"default_value": "latest",
						"server_value": "latest",
						"is_editable": true,
						"rules": "required|string|max:20"
					}
				}]
			}
		}
	},
	"meta": {
		"is_server_owner": true,
		"user_permissions": ["*"]
	}
}
JSON;
        } else {
        	$response = <<<'JSON'
{
  "object": "stats",
  "attributes": {
    "current_state": "starting",
    "is_suspended": false,
    "resources": {
      "memory_bytes": 588701696,
      "cpu_absolute": 0,
      "disk_bytes": 130156361,
      "network_rx_bytes": 694220,
      "network_tx_bytes": 337090
    }
  }
}
JSON;
      	}
      }
    // FIN DEBUG
*/
		$jsonResponse = json_decode(utf8_encode($response));
		if (json_last_error() != JSON_ERROR_NONE)  //erreur dans le traitement du fichier json
      		return json_last_error_msg();
      
      	return $jsonResponse;
	}

	// ******************* ENDPOINT => CLIENT *******************
	public function getListServers() {    
      	return $this->sendRequest("/api/client");
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
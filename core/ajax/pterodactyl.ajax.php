<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
  
// Classe pour les appels api


try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	
	//log::add('pterodactyl', 'debug', dirname(__FILE__) . '/../../resources/pterodactylApi.php'); 
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

  /* Fonction permettant l'envoi de l'entête 'Content-Type: application/json'
    En V3 : indiquer l'argument 'true' pour contrôler le token d'accès Jeedom
    En V4 : autoriser l'exécution d'une méthode 'action' en GET en indiquant le(s) nom(s) de(s) action(s) dans un tableau en argument
  */
    ajax::init();

	// Appel classe pour requetes API pterodactyl
  	require_once dirname(__FILE__) . '/../../resources/pterodactylApi.php';
  	include_file('core', 'pterodactyl', 'class', 'pterodactyl');
  
  	if (init('action') == 'sync') { 
		ajax::success(syncServers());
	}


    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
    /*     * *********Catch exeption*************** */
}
catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}


function syncServers() {
  	
	log::add('pterodactyl', 'debug', '======== SYNC PTERODACTYL ========');
    $p = new pterodactylApi(config::byKey('apiKey', 'pterodactyl'), config::byKey('pteroRootUrl', 'pterodactyl'), config::byKey('iAmAdmin', 'pterodactyl'));
    $response = $p->getListServers();
	log::add('pterodactyl', 'debug', "liste des serveurs: " . json_encode($response));
  	$detailServers = [];
  	$new = 0;
  	foreach ($response->data as $server) {
      
      	$identifier = 	$server->attributes->identifier;
      	$uuid = 		$server->attributes->uuid;
      	$name = 		$server->attributes->name;
      	$node = 		$server->attributes->node;

      
      	$allServers = eqLogic::byType('pterodactyl');
      	$alreadyExists = false;
        foreach($allServers as $s) {
            // le serveur est déjà est déjà connu
            if($identifier == $s->getLogicalId()) {
                // on actualise juste les infos
                $s->updateInfos();
                $alreadyExists = true;
            }
        }
		// on a parcouru la liste des serveurs existants et il n'y est pas, on le créé maintenant
        if(!$alreadyExists) {
            $newServ = new pterodactyl();
            $newServ->setName($name);
            $newServ->setIsEnable(1);
            $newServ->setIsVisible(0);
            $newServ->setLogicalId($identifier);
            $newServ->setEqType_name('pterodactyl');
            $newServ->save();

          	$newServ->updateMainInfos(); // enregistrement des infos principales, nom, node, ip
          	$newServ->updateInfos(); // on lance une actualisation dans la foulée pour que ça créé les commandes

          	$detailServers[] = [
              					'eqlogic' => $newServ->getId(),
              					'identifier' => $identifier,
                              	'uuid' => $uuid,
                              	'name' => $name,
                              	'node' => $node,
                             ];
          
            $new++;
        }

    }

	$return = ["new" => $new, "servers" => $detailServers];
	log::add('pterodactyl', 'debug', json_encode($return));
	return $return;  
}

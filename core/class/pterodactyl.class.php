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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

// Appel classe pour requetes API pterodactyl
require_once dirname(__FILE__) . '/../../resources/pterodactylApi.php';

class pterodactyl extends eqLogic {
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  
  //* Fonction exécutée automatiquement toutes les minutes par Jeedom
  public static function cron() {
  		foreach (self::byType('pterodactyl') as $eqLogicPterodactyl) {
			if($eqLogicPterodactyl->getIsEnable() == 1)
				$eqLogicPterodactyl->updateInfos();

			//log::add('pterodactyl', 'debug', 'update Serveur ' . $eqLogicPterodactyl->getName());
		}
  }
  

  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

  
  //* Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {
    		foreach (self::byType('pterodactyl') as $eqLogicPterodactyl) {
			if($eqLogicPterodactyl->getIsEnable() == 1)
				$eqLogicPterodactyl->updateMainInfos();

			//log::add('pterodactyl', 'debug', 'update Infos Principales Serveur ' . $eqLogicPterodactyl->getName());
		}
  }
  

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    $order = 1; // pour avoir toujours le même ordre dans les commandes

    // Nom du serv
    $info = $this->getCmd(null, 'name');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Nom', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('name');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'tile');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();

    // Node
    $info = $this->getCmd(null, 'node');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Node', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('node');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'tile');
    $info->setIsVisible(1);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();

    // Description
    $info = $this->getCmd(null, 'description');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Description', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('description');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(1);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();

    // ip
    $info = $this->getCmd(null, 'ip');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Adresse IP', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('ip');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'tile');
    $info->setIsVisible(1);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();    
    
    // ipAlias
    $info = $this->getCmd(null, 'ipAlias');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Alias de l\'adresse IP', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('ipAlias');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();
    
    // port
    $info = $this->getCmd(null, 'port');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Port', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('port');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'tile');
    $info->setIsVisible(1);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();
    
    // Uuid
    $info = $this->getCmd(null, 'uuid');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Identifiant interne', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('uuid');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
    
    // limitMemory
    $info = $this->getCmd(null, 'limitMemory');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Limite Mémoire', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('limitMemory');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();

    // limitSwap
    $info = $this->getCmd(null, 'limitSwap');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Limite Swap', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('limitSwap');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
    
    // limitDisk
    $info = $this->getCmd(null, 'limitDisk');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Limite Disk', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('limitDisk');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
        
    // limitIo
    $info = $this->getCmd(null, 'limitIo');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Limite IO', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('limitIo');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
        
    // limitCpu
    $info = $this->getCmd(null, 'limitCpu');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Limite CPU', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('limitCpu');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();
        
    // limitThreads
    $info = $this->getCmd(null, 'limitThreads');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Limite Threads', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('limitThreads');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(0);
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
    
    
   // ========= Informations supplémentaires via resources ======== //

    // currentState
    $info = $this->getCmd(null, 'currentState');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Statut', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('currentState');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'tile');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-3 months");
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();

    // isSuspended
    $info = $this->getCmd(null, 'isSuspended');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('Etat Suspendu', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('isSuspended');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setTemplate('dashboard', 'default'); //template pour le dashboard
    $info->setSubType('binary');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-3 months");
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
    
    // memoryBytes
    $info = $this->getCmd(null, 'memoryBytes');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('memory bytes', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('memoryBytes');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setUnite('Mo');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-1 month");
    $info->setDisplay('forceReturnLineBefore', true);
    $info->save();
    
    // cpuAbsolute
    $info = $this->getCmd(null, 'cpuAbsolute');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('cpu absolute', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('cpuAbsolute');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setIsVisible(0);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-1 month");
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();

    // diskBytes
    $info = $this->getCmd(null, 'diskBytes');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('disk bytes', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('diskBytes');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setUnite('Mo');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-1 month");
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();


    // networkRxBytes
    $info = $this->getCmd(null, 'networkRxBytes');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('networkRxBytes', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('networkRxBytes');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setUnite('Ko/s');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-1 month");
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();

    // networkTxBytes
    $info = $this->getCmd(null, 'networkTxBytes');
    if (!is_object($info)) {
      $info = new pterodactylCmd();
      $info->setName(__('networkTxBytes', __FILE__));
    }
    $info->setOrder($order++);
    $info->setLogicalId('networkTxBytes');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->setTemplate('dashboard', 'default');
    $info->setUnite('Ko/s');
    $info->setIsVisible(1);
    $info->setIsHistorized(1);
    $info->setConfiguration("historyPurge", "-1 month");
    $info->setDisplay('forceReturnLineBefore', false);
    $info->save();
	  
    // ######################### ACTIONS ######################### //

    // start
    $cmd = $this->getCmd('action', 'start');
    if (!is_object($cmd)) {
      $cmd = new pterodactylCmd();
      $cmd->setName(__('Démarrer', __FILE__));
    }
    $cmd->setOrder($order++);
    $cmd->setLogicalId('start');
    $cmd->setEqLogic_id($this->getId());
    $cmd->setType('action');
    $cmd->setSubType('other');
    $cmd->setIsVisible(1);
    $cmd->setTemplate('dashboard', 'pterodactyl::confirmBtn');
    $cmd->setTemplate('mobile', 'pterodactyl::confirmBtn');
    $cmd->setDisplay('forceReturnLineBefore', true);
    $cmd->save();

    // restart
    $cmd = $this->getCmd('action', 'restart');
    if (!is_object($cmd)) {
      $cmd = new pterodactylCmd();
      $cmd->setName(__('Redémarrer', __FILE__));
    }
    $cmd->setOrder($order++);
    $cmd->setLogicalId('restart');
    $cmd->setEqLogic_id($this->getId());
    $cmd->setType('action');
    $cmd->setSubType('other');
    $cmd->setIsVisible(1);
    $cmd->setTemplate('dashboard', 'pterodactyl::confirmBtn');
    $cmd->setTemplate('mobile', 'pterodactyl::confirmBtn');
    $cmd->setDisplay('forceReturnLineBefore', false);
    $cmd->save();

    // stop
    $cmd = $this->getCmd('action', 'stop');
    if (!is_object($cmd)) {
      $cmd = new pterodactylCmd();
      $cmd->setName(__('Arrêter', __FILE__));
    }
    $cmd->setOrder($order++);
    $cmd->setLogicalId('stop');
    $cmd->setEqLogic_id($this->getId());
    $cmd->setType('action');
    $cmd->setSubType('other');
    $cmd->setIsVisible(1);
    $cmd->setTemplate('dashboard', 'pterodactyl::confirmBtn');
    $cmd->setTemplate('mobile', 'pterodactyl::confirmBtn');
    $cmd->setDisplay('forceReturnLineBefore', false);
    $cmd->save();

    // kill
    $cmd = $this->getCmd('action', 'kill');
    if (!is_object($cmd)) {
      $cmd = new pterodactylCmd();
      $cmd->setName(__('Kill', __FILE__));
    }
    $cmd->setOrder($order++);
    $cmd->setLogicalId('kill');
    $cmd->setEqLogic_id($this->getId());
    $cmd->setType('action');
    $cmd->setSubType('other');
    $cmd->setIsVisible(1);
    $cmd->setTemplate('dashboard', 'pterodactyl::confirmBtn');
    $cmd->setTemplate('mobile', 'pterodactyl::confirmBtn');
    $cmd->setDisplay('forceReturnLineBefore', false);
    $cmd->save();
    
    // rafraichir
    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
        $refresh = new pterodactylCmd();
        $refresh->setName(__('Rafraîchir', __FILE__));
    }
    $refresh->setOrder($order++);
	$refresh->setEqLogic_id($this->getId());
	$refresh->setLogicalId('refresh');
	$refresh->setType('action');
	$refresh->setSubType('other');
	$refresh->save();
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }
  */

  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {}
  */

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*     * **********************Getteur Setteur*************************** */
  	public function updateMainInfos() {
    	$identifier = $this->getLogicalId();
      	$p = new pterodactylApi(config::byKey('apiKey', 'pterodactyl'), config::byKey('pteroRootUrl', 'pterodactyl'), config::byKey('iAmAdmin', 'pterodactyl'));
      	$details = $p->getServerDetails($identifier);
      	//log::add('pterodactyl', 'debug', "Détail du serveur: " . $identifier . ": " . json_encode($details));
      	log::add('pterodactyl', 'debug', "Détail du serveur: " . json_encode($details->attributes));
      	$name = 		$details->attributes->name;
      	$node = 		$details->attributes->node;
      	$description = 		$details->attributes->description;
      	$uuid = 		$details->attributes->uuid;
      	$limitMemory = 		$details->attributes->limits->memory;
      	$limitSwap = 		$details->attributes->limits->swap;
      	$limitDisk = 		$details->attributes->limits->disk;
      	$limitIo = 		$details->attributes->limits->io;
      	$limitCpu = 		$details->attributes->limits->cpu;
      	$limitThreads = 	$details->attributes->limits->threads;
      	$ip = 			$details->attributes->relationships->allocations->data[0]->attributes->ip;
      	$ipAlias =		$details->attributes->relationships->allocations->data[0]->attributes->ipAlias;
      	$port = 		$details->attributes->relationships->allocations->data[0]->attributes->port;

      	$this->checkAndUpdateCmd("name", $name);
      	$this->checkAndUpdateCmd("node", $node);
      	$this->checkAndUpdateCmd("description", $description);
      	$this->checkAndUpdateCmd("uuid", $uuid);
      	$this->checkAndUpdateCmd("ip", $ip);
      	$this->checkAndUpdateCmd("ipAlias", $ipAlias);
      	$this->checkAndUpdateCmd("port", $port);
	$this->checkAndUpdateCmd("limitMemory", round(($limitMemory/1024/1024), 2));
	$this->checkAndUpdateCmd("limitSwap", round(($limitSwap/1024/1024), 2));
	$this->checkAndUpdateCmd("limitDisk", round(($limitDisk/1024/1024), 2));
	$this->checkAndUpdateCmd("limitIo", $limitIo);
      	$this->checkAndUpdateCmd("limitCpu", $limitCpu);
        $this->checkAndUpdateCmd("limitThreads", $limitThreads);	
      	
    }
  
	public function updateInfos() {
    	$identifier = $this->getLogicalId();
      	$p = new pterodactylApi(config::byKey('apiKey', 'pterodactyl'), config::byKey('pteroRootUrl', 'pterodactyl'), config::byKey('iAmAdmin', 'pterodactyl'));
      	$resources = $p->getResourcesUsage($identifier);
      	//log::add('pterodactyl', 'debug', "Détail du serveur: " . $identifier . ": " . json_encode($resources));
      
      	$currentState = 	$resources->attributes->current_state;
      	$isSuspended = 		$resources->attributes->is_suspended;
      	$memoryBytes = 		$resources->attributes->resources->memory_bytes;
	$cpuAbsolute = 		$resources->attributes->resources->cpu_absolute;
	$diskBytes = 		$resources->attributes->resources->disk_bytes;
	$networkRxBytes = 	$resources->attributes->resources->network_rx_bytes;
      	$networkTxBytes = 	$resources->attributes->resources->network_tx_bytes;
      
      	$this->checkAndUpdateCmd("currentState", $currentState);
      	$this->checkAndUpdateCmd("isSuspended", $isSuspended);
	$this->checkAndUpdateCmd("memoryBytes", round(($memoryBytes/1024/1024), 2));
	$this->checkAndUpdateCmd("cpuAbsolute", $cpuAbsolute);
	$this->checkAndUpdateCmd("diskBytes", round(($diskBytes/1024/1024), 2));
	$this->checkAndUpdateCmd("networkRxBytes", round(($networkRxBytes/1024), 2));
      	$this->checkAndUpdateCmd("networkTxBytes", round(($networkTxBytes/1024), 2));
      
    }
  
  public function changeState($newState) {
    log::add("pterodactyl", "debug", "Changement d'état demandé: " . $newState);
    $identifier = $this->getLogicalId();
  	$p = new pterodactylApi(config::byKey('apiKey', 'pterodactyl'), config::byKey('pteroRootUrl', 'pterodactyl'), config::byKey('iAmAdmin', 'pterodactyl'));
       
    $response = $p->postChangeState($identifier, $newState);
    //log::add("pterodactyl", "debug", "reponse: " . $response);
    sleep(10); // ajoute une petite tempo pour récupérer le nouvel état dans la foulée
    self::updateInfos();
  }
}

class pterodactylCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
    $eqLogic = $this->getEqLogic(); // Récupération de l’eqlogic
	
    switch ($this->getLogicalId()) {
      	case 'start':
        	$eqLogic->changeState('start');
        	break;
        case 'stop':
        	$eqLogic->changeState('stop');
            break;
        case 'restart':
        	$eqLogic->changeState('restart');
            break;
        case 'kill':
        	$eqLogic->changeState('kill');
            break;
	case 'refresh': 
        	$eqLogic->updateMainInfos();
		$eqLogic->updateInfos();
		break;
	default:
		throw new Error('This should not append!');
		log::add('pterodactyl', 'warn', 'Error while executing cmd ' . $this->getLogicalId());
		break;
    }
    
    
    return;
  }

  /*     * **********************Getteur Setteur*************************** */

}

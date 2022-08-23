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
	
	*/
	//public static $_widgetPossibility = array("custom" => true);
	/*
	* Permet de crypter/décrypter automatiquement des champs de configuration du plugin
	* Exemple : "param1" & "param2" seront cryptés mais pas "param3"
	public static $_encryptConfigKey = array('param1', 'param2');
	*/

	/*     * ***********************Methode static*************************** */

	
	//* Fonction exécutée automatiquement toutes les minutes par Jeedom
	public static function cron() {
		foreach (self::byType('pterodactyl') as $eqLogicPterodactyl) {
			if($eqLogicPterodactyl->getIsEnable() == 1) {
				$eqLogicPterodactyl->updateInfos();
				$eqLogicPterodactyl->updatePlayers();
            }
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
      
      	// si on active/désactive une instance, ça fait pareil pour les serveurs liés
      	if($this->getConfiguration('type') == "instance") {
        	$instanceIsEnable = $this->getIsEnable();
              $instanceId = $this->getId();
              $eqLogicsServer = eqLogic::byTypeAndSearhConfiguration("pterodactyl", '"type":"server"');
              foreach($eqLogicsServer as $server) {
              	if($server->getConfiguration('instanceId') == $instanceId) {
                	$server->setIsEnable($instanceIsEnable);
                  	$server->save();
                  
                  	self::toggleConsole($server); //on fait suivre la console dans le même état
                }
              }
        }
      	self::toggleConsole($this);

    }

  	private function toggleConsole($serv) {
      	$display = $serv->getConfiguration('displayTileConsole','');
      
		// si on demande à voir la console, on l'active et on la place au meme endroit sur le dashboard
		$logicalId = $serv->getLogicalId();
		$currentDashboard = $serv->getObject_id();
		$displayMainEq = $serv->getIsEnable();
      	log::add('pterodactyl', 'debug', "logicalId: $logicalId currentDash: $currentDashboard displayMaineq: $displayMainEq");
		$eqConsole = eqLogic::byLogicalId($logicalId . '_console', 'pterodactyl');
		if(is_object($eqConsole)) {
			log::add('pterodactyl', 'debug', "eqConsole!");
			if($display == 1 && $displayMainEq == 1) { // si on demande la tuile console ET que léquipement principal est actif
				$eqConsole->setIsEnable(1);
				$eqConsole->setIsVisible(1);
				$eqConsole->setObject_id($currentDashboard);
              	log::add('pterodactyl', 'debug', "on display");
			} else {
				$eqConsole->setIsEnable(0);
				$eqConsole->setIsVisible(0);
              	log::add('pterodactyl', 'debug', "on display pas");
			}
			$eqConsole->save();
		}
    }
  
	// Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
	public function preSave() {
	}
  
	// Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
	public function postSave() {
		if($this->getConfiguration('type','') == "instance")
           return; // les instances ont pas de cmd, juste de la configuration
          
		if($this->getConfiguration('type','') == "console") { // si c'est la console correspondante on créé juste une info
          $info = $this->getCmd(null, 'displayConsole');
          if (!is_object($info)) {
            log::add('pterodactyl', 'debug', 'créa console');
            $info = new pterodactylCmd();
            $info->setName(__('Retour de la console', __FILE__));
          }
          $info->setOrder(1);
          $info->setLogicalId('displayConsole');
          $info->setConfiguration('type', 'console');
          $info->setEqLogic_id($this->getId());
          $info->setType('info');
          $info->setSubType('string');
          $info->setTemplate('dashboard', 'pterodactyl::console');
          $info->setTemplate('mobile', 'pterodactyl::console');
          $info->setIsVisible(1);
          $info->setIsHistorized(0);
          $info->setDisplay('title_disable', true); // @TODO mettre les bons params
          $info->setDisplay('hide_name', true);
          $info->setDisplay('forceReturnLineBefore', true);
          $info->setDisplay('forceReturnLineAfter', true);
          $info->save();      

          return;
		}

		// Nom du serv
		$info = $this->getCmd(null, 'name');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Nom', __FILE__));
		}
		$info->setOrder(1);
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
		$info->setOrder(2);
		$info->setLogicalId('node');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setTemplate('dashboard', 'tile');
		$info->setIsVisible(1);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// Description
		$info = $this->getCmd(null, 'description');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Description', __FILE__));
		}
		$info->setOrder(3);
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
		$info->setOrder(4);
		$info->setLogicalId('ip');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setTemplate('dashboard', 'tile');
		$info->setTemplate('mobile', 'tile');
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
		$info->setOrder(5);
		$info->setLogicalId('ipAlias');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setTemplate('dashboard', 'default');
		$info->setIsVisible(1);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// port
		$info = $this->getCmd(null, 'port');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Port', __FILE__));
		}
		$info->setOrder(6);
		$info->setLogicalId('port');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'tile');
		$info->setTemplate('mobile', 'tile');
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
		$info->setOrder(7);
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
		$infoLimitMemory = $this->getCmd(null, 'limitMemory');
		if (!is_object($infoLimitMemory)) {
			$infoLimitMemory = new pterodactylCmd();
			$infoLimitMemory->setName(__('Limite Mémoire', __FILE__));
		}
		$infoLimitMemory->setOrder(8);
		$infoLimitMemory->setLogicalId('limitMemory');
		$infoLimitMemory->setEqLogic_id($this->getId());
		$infoLimitMemory->setType('info');
		$infoLimitMemory->setSubType('numeric');
		$infoLimitMemory->setTemplate('dashboard', 'default');
		$infoLimitMemory->setUnite('Go');
		$infoLimitMemory->setIsVisible(0);
		$infoLimitMemory->setIsHistorized(0);
		$infoLimitMemory->setDisplay('forceReturnLineBefore', true);
		$infoLimitMemory->save();

		// limitSwap => on ne récupère pas de valeur courante, utile?
		$info = $this->getCmd(null, 'limitSwap');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Limite Swap', __FILE__));
		}
		$info->setOrder(9);
		$info->setLogicalId('limitSwap');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'default');
		$info->setUnite('Go');
		$info->setIsVisible(0);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// limitDisk
		$infoLimitDisk = $this->getCmd(null, 'limitDisk');
		if (!is_object($infoLimitDisk)) {
			$infoLimitDisk = new pterodactylCmd();
			$infoLimitDisk->setName(__('Limite Disk', __FILE__));
		}
		$infoLimitDisk->setOrder(10);
		$infoLimitDisk->setLogicalId('limitDisk');
		$infoLimitDisk->setEqLogic_id($this->getId());
		$infoLimitDisk->setType('info');
		$infoLimitDisk->setSubType('numeric');
		$infoLimitDisk->setTemplate('dashboard', 'default');
		$infoLimitDisk->setUnite('Go');
		$infoLimitDisk->setIsVisible(0);
		$infoLimitDisk->setIsHistorized(0);
		$infoLimitDisk->setDisplay('forceReturnLineBefore', true);
		$infoLimitDisk->save();


		// limitCpu
		$infoLimitCpu = $this->getCmd(null, 'limitCpu');
		if (!is_object($infoLimitCpu)) {
			$infoLimitCpu = new pterodactylCmd();
			$infoLimitCpu->setName(__('Limite CPU', __FILE__));
		}
		$infoLimitCpu->setOrder(11);
		$infoLimitCpu->setLogicalId('limitCpu');
		$infoLimitCpu->setEqLogic_id($this->getId());
		$infoLimitCpu->setType('info');
		$infoLimitCpu->setSubType('numeric');
		$infoLimitCpu->setTemplate('dashboard', 'default');
		$infoLimitCpu->setIsVisible(0);
		$infoLimitCpu->setIsHistorized(0);
		$infoLimitCpu->setDisplay('forceReturnLineBefore', false);
		$infoLimitCpu->save();

		// limitIo
		$info = $this->getCmd(null, 'limitIo');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Limite IO', __FILE__));
		}
		$info->setOrder(12);
		$info->setLogicalId('limitIo');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'default');
		$info->setIsVisible(0);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// limitThreads
		$info = $this->getCmd(null, 'limitThreads');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Limite Threads', __FILE__));
		}
		$info->setOrder(13);
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
		$info->setOrder(14);
		$info->setLogicalId('currentState');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setTemplate('dashboard', 'tile');
		$info->setTemplate('mobile', 'tile');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-3 months");
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// Uptime
		$info = $this->getCmd(null, 'uptime');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Uptime', __FILE__));
		}
		$info->setOrder(15);
		$info->setLogicalId('uptime');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->setTemplate('dashboard', 'default');
		$info->setIsVisible(1);
		$info->setIsHistorized(0);
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// networkRxBytes
		$info = $this->getCmd(null, 'networkRxBytes');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Réception données', __FILE__));
		}
		$info->setOrder(16);
		$info->setLogicalId('networkRxBytes');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'line');
		$info->setUnite('Mo');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-1 month");
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// networkTxBytes
		$info = $this->getCmd(null, 'networkTxBytes');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Envoi données', __FILE__));
		}
		$info->setOrder(17);
		$info->setLogicalId('networkTxBytes');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'line');
		$info->setUnite('Mo');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-1 month");
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// isSuspended
		$info = $this->getCmd(null, 'isSuspended');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Etat Suspendu', __FILE__));
		}
		$info->setOrder(18);
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
			$info->setName(__('RAM', __FILE__));
		}
		$info->setOrder(19);
		$info->setLogicalId('memoryBytes');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'pterodactyl::defaultDisplayMax');
		$info->setTemplate('mobile', 'pterodactyl::defaultDisplayMax');
		$info->setConfiguration('minValue', 0);
		$currentLimitMemory = $infoLimitMemory->execCmd();
		if(floatval($currentLimitMemory) > 0) {
			$info->setConfiguration('maxValue', floatval($currentLimitMemory));
		} else {
			$info->setConfiguration('maxValue', 1024); //on définie 1024, soit 1To
		}
		$info->setUnite('Go');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-1 month");
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();
		
		// cpuAbsolute
		$info = $this->getCmd(null, 'cpuAbsolute');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('CPU', __FILE__));
		}
		$info->setOrder(20);
		$info->setLogicalId('cpuAbsolute');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'pterodactyl::customCpuUsage');
		$info->setTemplate('mobile', 'pterodactyl::customCpuUsage');
		$info->setConfiguration('minValue', 0);
		$currentMaxCpu = $infoLimitCpu->execCmd();
		if(floatval($currentMaxCpu) > 0) {
			$info->setConfiguration('maxValue', floatval($currentMaxCpu));
		} else {
			$info->setConfiguration('maxValue', 1000); //on définie 1000, soit 10 coeurs
		}
		$info->setUnite('%');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-1 month");
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();

		// diskBytes
		$info = $this->getCmd(null, 'diskBytes');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Espace disque', __FILE__));
		}
		$info->setOrder(21);
		$info->setLogicalId('diskBytes');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard', 'pterodactyl::defaultDisplayMax');
		$info->setTemplate('mobile', 'pterodactyl::defaultDisplayMax');
		$info->setConfiguration('minValue', 0);
		$currentLimitDisk = $infoLimitDisk->execCmd();
		if(floatval($currentLimitDisk) > 0) {
			$info->setConfiguration('maxValue', floatval($currentLimitDisk));
		} else {
			$info->setConfiguration('maxValue', 999999); //on définie 9999999999999 pour afficher infini dans le template
		}
		$info->setUnite('Go');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-1 month");
		$info->setDisplay('forceReturnLineBefore', false);
		$info->save();
		

		// Nombre de joueurs en ligne
		$info = $this->getCmd(null, 'playersOnline');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Joueurs en ligne', __FILE__));
		}
		$info->setOrder(22);
		$info->setLogicalId('playersOnline');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setTemplate('dashboard', 'tile');
		$info->setTemplate('mobile', 'tile');
		$info->setIsVisible(1);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-6 months");
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();

		// Nombre de joueurs max
		$info = $this->getCmd(null, 'playersMax');
		if (!is_object($info)) {
			$info = new pterodactylCmd();
			$info->setName(__('Joueurs max', __FILE__));
		}
		$info->setOrder(23);
		$info->setLogicalId('playersMax');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setConfiguration('minValue', 0);
		$info->setTemplate('dashboard', 'tile');
		$info->setTemplate('mobile', 'tile');
		$info->setIsVisible(0);
		$info->setIsHistorized(1);
		$info->setConfiguration("historyPurge", "-6 months");
		$info->setDisplay('forceReturnLineBefore', true);
		$info->save();
		// ######################### ACTIONS ######################### //

		// start
		$cmd = $this->getCmd('action', 'start');
		if (!is_object($cmd)) {
			$cmd = new pterodactylCmd();
			$cmd->setName(__('Démarrer', __FILE__));
		}
		$cmd->setOrder(24);
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
		$cmd->setOrder(25);
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
		$cmd->setOrder(26);
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
		$cmd->setOrder(27);
		$cmd->setLogicalId('kill');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setIsVisible(1);
		$cmd->setTemplate('dashboard', 'pterodactyl::confirmBtn');
		$cmd->setTemplate('mobile', 'pterodactyl::confirmBtn');
		$cmd->setDisplay('forceReturnLineBefore', false);
		$cmd->save();

		// envoi commande
		$cmd = $this->getCmd(null, 'sendCmd');
		if (!is_object($cmd)) {
			$cmd = new pterodactylCmd();
			$cmd->setName(__('Envoi commande', __FILE__));
		}
		$cmd->setOrder(28);
		$cmd->setLogicalId('sendCmd');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setTemplate('dashboard', 'tile'); //template pour le dashboard
		$cmd->setDisplay('title_disable', 1);
		$cmd->setDisplay('message_disable', 0);
		$cmd->setDisplay('message_placeholder', 'Commande à envoyer');
		$cmd->setSubType('message');
		$cmd->setIsVisible(1);
		$cmd->setDisplay('forceReturnLineBefore', true);
		$cmd->save();
		
		// rafraichir
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new pterodactylCmd();
			$refresh->setName(__('Rafraîchir', __FILE__));
		}
		$refresh->setOrder(29);
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();
	}

	// Fonction exécutée automatiquement avant la suppression de l'équipement
	public function preRemove() {
      	if($this->getConfiguration('type') == "instance") { // suppression instance = suppression des serveurs associés
              $instanceId = $this->getId();
              $eqLogicsServer = eqLogic::byTypeAndSearhConfiguration("pterodactyl", '"type":"server"');
              foreach($eqLogicsServer as $server) {
              	if($server->getConfiguration('instanceId') == $instanceId)
                  	$server->remove();
              }
        }
	}

	// Fonction exécutée automatiquement après la suppression de l'équipement
	public function postRemove() { 
      	if($this->getConfiguration('type') != "server")
          return;
      
		$logicalId = $this->getLogicalId();
		log::add('pterodactyl', 'debug', "suppression console associée : ");
		$eqConsole = eqLogic::byLogicalId($logicalId . '_console', 'pterodactyl');
      	if(is_object($eqConsole))
          $eqConsole->remove();
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

	
	//* Permet de modifier l'affichage du widget (également utilisable par les commandes)
	/*public function toHtml($_version = 'dashboard') {}*/

	

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

        if($this->getConfiguration('type','') != "server")
          return;

        $infosInstance = self::getInfosFromInstance($this->getConfiguration('instanceId',''));
        $p = new pterodactylApi($infosInstance['apiKey'], $infosInstance['pteroRootUrl'], $infosInstance['iAmAdmin']);
      
		$details = $p->getServerDetails($identifier);

		log::add('pterodactyl', 'debug', "function updateMainInfos() : " . json_encode($details->attributes));

		$name = 			$details->attributes->name;
		$node = 			$details->attributes->node;
		$description = 		$details->attributes->description;
		$uuid = 			$details->attributes->uuid;
		$limitMemory = 		$details->attributes->limits->memory;
		$limitSwap = 		$details->attributes->limits->swap;
		$limitDisk = 		$details->attributes->limits->disk;
		$limitIo = 			$details->attributes->limits->io;
		$limitCpu = 		$details->attributes->limits->cpu;
		$limitThreads = 	$details->attributes->limits->threads;
      
      	// si on a plusieurs allocations pour un meme serveur, il faut récupérer les infos de celui qui a le flag is_default pour l'interroger
      	foreach($details->attributes->relationships->allocations->data as $data) {
          if($data->attributes->is_default == true) {
              $ip = 		$data->attributes->ip;
              $ipAlias =	(empty($data->attributes->ip_alias)) ? "" : $data->attributes->ip_alias;
              $port = 		$data->attributes->port;
          }
        }

		$limitMemory = round(($limitMemory/1024), 2);
		$limitSwap = round(($limitSwap/1024), 2);
		$limitDisk = round(($limitDisk/1024), 2);

		$this->checkAndUpdateCmd("name", $name);
		$this->checkAndUpdateCmd("node", $node);
		$this->checkAndUpdateCmd("description", $description);
		$this->checkAndUpdateCmd("uuid", $uuid);
		$this->checkAndUpdateCmd("ip", $ip);
		$this->checkAndUpdateCmd("ipAlias", $ipAlias);
		$this->checkAndUpdateCmd("port", $port);
		$this->checkAndUpdateCmd("limitMemory", $limitMemory);
		$this->checkAndUpdateCmd("limitSwap", $limitSwap);
		$this->checkAndUpdateCmd("limitDisk", $limitDisk);
		$this->checkAndUpdateCmd("limitIo", $limitIo);
		$this->checkAndUpdateCmd("limitCpu", $limitCpu);
		$this->checkAndUpdateCmd("limitThreads", $limitThreads);	

		// Mise à jour des limites/maxValue dans les commandes infos des valeurs actuelles
		$updateMemoryBytes = $this->getCmd(null, 'memoryBytes');
		if (is_object($updateMemoryBytes)) {
			$maxValue = (floatval($limitMemory) > 0) ? floatval($limitMemory) : 1024; // 1To pour afficher infini
			$updateMemoryBytes->setConfiguration('maxValue', $maxValue);
			$updateMemoryBytes->save();
		}

		$updateDiskBytes = $this->getCmd(null, 'diskBytes');
		if (is_object($updateDiskBytes)) {
			$maxValue = (floatval($limitDisk) > 0) ? floatval($limitDisk) : 999999; /// pour afficher infini
			$updateDiskBytes->setConfiguration('maxValue', $maxValue);
			$updateDiskBytes->save();
		}

		$updateCpuAbsolute = $this->getCmd(null, 'cpuAbsolute');
		if (is_object($updateCpuAbsolute)) {
			$maxValue = (floatval($limitCpu) > 0) ? floatval($limitCpu) : 1000; // 10 coeurs
			$updateCpuAbsolute->setConfiguration('maxValue', $maxValue);
			$updateCpuAbsolute->save();
		}

		$this->refreshWidget();
	}
	
	private function getInfosFromInstance($instanceId) {
  		$eqLogic = eqLogic::byId($instanceId);
      	if(!is_object($eqLogic))
          throw new Error('This should not append!');
      
      	return["apiKey" => $eqLogic->getConfiguration('apiKey', ''),
               "pteroRootUrl" => $eqLogic->getConfiguration('pteroRootUrl', ''),
               "iAmAdmin" => $eqLogic->getConfiguration('iAmAdmin', '')
               ];
          
  	}
  
	public function updateInfos() {
		$identifier = $this->getLogicalId();
      
      	if($this->getConfiguration('type','') != "server")
          return;
      
        $infosInstance = self::getInfosFromInstance($this->getConfiguration('instanceId',''));
        $p = new pterodactylApi($infosInstance['apiKey'], $infosInstance['pteroRootUrl'], $infosInstance['iAmAdmin']);
      
        $resources = $p->getResourcesUsage($identifier);
        log::add('pterodactyl', 'debug', "function updateInfos() : " . $identifier . ": " . json_encode($resources));

        $currentState = 	$resources->attributes->current_state;
        if($currentState == "") {
          if($resources->errors[0]->status == "409") { // erreur renvoyé si suspendu
            $currentState = "Suspended";
          } else {
            $currentState = "Unable to contact Server";
            log::add('pterodactyl', 'debug', "Erreur de mise à jour des infos. détail: " . json_encode($resources));
          }
        }
        $memoryBytes = 		$resources->attributes->resources->memory_bytes;
        $cpuAbsolute = 		$resources->attributes->resources->cpu_absolute;
        $diskBytes = 		$resources->attributes->resources->disk_bytes;
        $networkRxBytes = 	$resources->attributes->resources->network_rx_bytes;
        $networkTxBytes = 	$resources->attributes->resources->network_tx_bytes;
        $uptime = 			$resources->attributes->resources->uptime; // donné en ms

        $this->checkAndUpdateCmd("currentState", $currentState);
        $this->checkAndUpdateCmd("isSuspended", $isSuspended);
        $this->checkAndUpdateCmd("memoryBytes", round(($memoryBytes/1024/1024/1024), 2));
        $this->checkAndUpdateCmd("cpuAbsolute", $cpuAbsolute);
        $this->checkAndUpdateCmd("diskBytes", round(($diskBytes/1024/1024/1024), 2));
        $this->checkAndUpdateCmd("networkRxBytes", round(($networkRxBytes/1024/1024), 2));
        $this->checkAndUpdateCmd("networkTxBytes", round(($networkTxBytes/1024/1024), 2));
        $this->checkAndUpdateCmd("uptime", $this->secondsToTime(round($uptime/1000)));
		
	}

	public function updatePlayers() {
      
      	if($this->getConfiguration('type','') != "server")
          return;
		
      
      	$ipAlias = $this->getCmd(null, 'ipAlias');
      	if(is_object($ipAlias))
      		$ipAlias = $ipAlias->execCmd();

        $ip = $this->getCmd(null, 'ip');
        if(is_object($ip))
          $ip = $ip->execCmd();
      	
      
      	$pingIp = (isset($ipAlias) && $ipAlias !== false && $ipAlias != "0") ? $ipAlias : $ip;
		$port = $this->getCmd(null, 'port');
      	if(is_object($port))
        	$pingPort = $port->execCmd();
            
		$game = $this->getConfiguration('game','');

      if($game == "minecraft") {
			$url = "https://minecraft-api.com/api/ping/" . $pingIp . "/" . $pingPort . "/json";
			log::add('pterodactyl', 'debug', '[API PLAYERS] Url ping = ' . $url);

            if(filter_var($pingIp, FILTER_VALIDATE_IP)) { // on vérifie si on a une ip et qu'elle est pas locale
                if(!filter_var($pingIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE))
                    return;
            } else {
                if(!filter_var($pingIp, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) // on vérifie que l'alias soit un domaine correct
                  return;
            }
          
			$content = @file_get_contents($url);

        	$json = json_decode($content);
			if(is_object($json)) {
				$online = $json->players->online;
				$max = $json->players->max;
				//log::add('pterodactyl', 'debug', "$online / $max joueurs");
				$this->checkAndUpdateCmd("playersOnline", $online);
				$this->checkAndUpdateCmd("playersMax", $max);
			} else {
				log::add('pterodactyl', 'debug', "[API PLAYERS] Lecture des infos impossible: $content");
			}
		} // FIN IF MINECRAFT
	}
	
	public function changeState($newState) {
		log::add("pterodactyl", "debug", "Changement d'état demandé: " . $newState);
		$identifier = $this->getLogicalId();

        $infosInstance = self::getInfosFromInstance($this->getConfiguration('instanceId',''));
        $p = new pterodactylApi($infosInstance['apiKey'], $infosInstance['pteroRootUrl'], $infosInstance['iAmAdmin']);

		$response = $p->postChangeState($identifier, $newState);
		sleep(10); // ajoute une petite tempo pour récupérer le nouvel état dans la foulée
		self::updateInfos();
	}
	
	public function sendCmd($message) {
		$identifier = $this->getLogicalId();

        $infosInstance = self::getInfosFromInstance($this->getConfiguration('instanceId',''));
        $p = new pterodactylApi($infosInstance['apiKey'], $infosInstance['pteroRootUrl'], $infosInstance['iAmAdmin']);
		
		$response = $p->postSendCommand($identifier, str_replace(['"',"'"], "", trim($message))); //trim  + vire les quotes car la console accepte pas
      	// @TODO gestion codes HTTP 502 si offline comme dit dans la doc?
		

	}
	
	private function secondsToTime($seconds) {
		$dtF = new \DateTime('@0');
		$dtT = new \DateTime("@$seconds");
		$days = $dtF->diff($dtT)->format('%a');
		$hours = $dtF->diff($dtT)->format('%h');
		$mins = $dtF->diff($dtT)->format('%i');
		$secs = $dtF->diff($dtT)->format('%s');
		
		$ret = "";
		$ret .= ($days > 0) ? ($days.' '.($days == 1 ? 'jour' : 'jours')) : "";
		$ret .= (($days > 0) && ($hours > 0 || $mins > 0)) ? ", " : "";
		$ret .= ($hours > 0) ? ($hours.' '.($hours == 1 ? 'heure' : 'heures')) : "";
		$ret .= ($hours > 0 && $mins > 0) ? ", " : "";
		$ret .= ($mins > 0) ? ($mins.' '.($mins == 1 ? 'minute' : 'minutes')) : ""; // . " "
		$ret .= (($days > 0 || $hours > 0 || $mins > 0) && $secs > 0)  ? " et " : "";
		$ret .= ($secs > 0) ? ($secs.' '.($secs == 1 ? 'seconde' : 'secondes')) : "";
		return trim($ret);
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
			case 'sendCmd':
			$eqLogic->sendCmd($_options['message']);
			break;
			case 'refresh': 
			$eqLogic->updateMainInfos();
			$eqLogic->updateInfos();
            $eqLogic->updatePlayers();
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
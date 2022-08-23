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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
function pterodactyl_install() {
  config::save('multipleInstance', 'ok', 'pterodactyl');
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function pterodactyl_update() {
	
  	$multipleInstance = config::byKey('multipleInstance', 'pterodactyl', 'nok', true);
  	// création d'une instance principale pour rattacher les serveurs - MAJ MULTI INSTANCE
  	if($multipleInstance != "ok") {
      $newInstance = new pterodactyl();
      $newInstance->setName("Instance 1");   	
      $newInstance->setIsEnable(1);          	
      $newInstance->setIsVisible(0);
      $newInstance->setConfiguration('type', 'instance');
      $newInstance->setConfiguration('pteroRootUrl',config::byKey('pteroRootUrl', 'pterodactyl'));
      $newInstance->setConfiguration('apiKey',config::byKey('apiKey', 'pterodactyl'));
      $newInstance->setConfiguration('iAmAdmin',config::byKey('iAmAdmin', 'pterodactyl'));
      $newInstance->setEqType_name('pterodactyl');
      $newInstance->save();
      
      $instanceId = $newInstance->getId();
      // marqueur passage multi instance fait
      config::save('multipleInstance', 'ok', 'pterodactyl');
      //on supprime lancienne config générale vu que c'est par instance maintenant
	  config::remove('pteroRootUrl', 'pterodactyl');
      config::remove('apiKey', 'pterodactyl');
      config::remove('iAmAdmin', 'pterodactyl');
    }
  
	foreach (pterodactyl::byType('pterodactyl', true) as $pterodactyl) {
		try {
          	// MAJ passage mono instance à multi instance
          	if($multipleInstance != "ok" && $pterodactyl->getConfiguration('type') == "") { 
                  $pterodactyl->setConfiguration('type', 'server');
                  $pterodactyl->setConfiguration('instanceId', $instanceId);              
            }
          	// ré-enregistrement des équipements
			$pterodactyl->save();
          	// actualisation du widget
          	$pterodactyl->refreshWidget();	
		} catch (Exception $e) {
			//throw new Exception(__('Erreur lors de la sauvegarde ', __FILE__));
		}
	}
  
  	return;
  
}

// Fonction exécutée automatiquement après la suppression du plugin
function pterodactyl_remove() {
}
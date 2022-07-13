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
include_file('core', 'authentification', 'php');
if (!isConnect()) {
  include_file('desktop', '404', 'php');
  die();
}
?>
<form class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Url de votre pterodactyl}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez la racine de votre pterodactyl (ex: https://mypterodactyl.io) sans le / de fin}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="pteroRootUrl"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Clé API pour les requêtes}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Clé API de l'utilisateur qui fera les requêtes}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="apiKey"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Je suis admin}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Si vous avez créé une clé API Application, cochez cette case, plus d'informations dans la documentation du plugin}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input type="checkbox" class="configKey form-control" data-l1key="iAmAdmin"/>
      </div>
    </div>
	<div class="form-group">
          <div class="col-md-4"></div>
          <div class="col-md-6">
          		<i>* aide pour avoir une clé api : <a href="https://dashflo.net/docs/api/pterodactyl/v1/">
      				https://dashflo.net/docs/api/pterodactyl/v1/
				</a></i>
          </div>
	</div>
  </fieldset>
</form>

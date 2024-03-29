<?php
	if (!isConnect('admin')) {
		throw new Exception('{{401 - Accès non autorisé}}');
	}
	// Déclaration des variables obligatoires
	$plugin = plugin::byId('pterodactyl');
	sendVarToJS('eqType', $plugin->getId());
	$eqLogicsInstances = eqLogic::byTypeAndSearhConfiguration($plugin->getId(), '"type":"instance"');
	$eqLogics = eqLogic::byType($plugin->getId());

	$infosPteroServ = [];
	foreach ($eqLogics as $eqLogic) {
		if($eqLogic->getConfiguration('type', '') != 'server')
			continue;
      
		$current = [];
		$name = $eqLogic->getCmd(null, 'name');
		$name = $name->execCmd();
		$node = $eqLogic->getCmd(null, 'node');
		$node = $node->execCmd();
		$uuid = $eqLogic->getCmd(null, 'uuid');
		$uuid = $uuid->execCmd();
		$ip = $eqLogic->getCmd(null, 'ip');
		$ip = $ip->execCmd();
		$ipAlias = $eqLogic->getCmd(null, 'ipAlias');
		$ipAlias = $ipAlias->execCmd();
      	$ipAlias = ($ipAlias != "" && $ipAlias != 0) ? " (" . $ipAlias . ")" : "";
		$port = $eqLogic->getCmd(null, 'port');
		$port = $port->execCmd();
		$current["nomnode"] = $name . " / node: " . $node;
		$current["uuid"] = $uuid;
		$current["ipport"] = $ip . ":" . $port . $ipAlias;
		$infosPteroServ[$eqLogic->getId()] = $current;
	}
	$logLevel = log::convertLogLevel(log::getLogLevel('pterodactyl')); // pour n'afficher les équipements liés qu'en mode debug
	$infosPteroServ['loglevel'] = $logLevel; // @TODO inutilisé pour le moment

	sendVarToJS('infosPteroServ', $infosPteroServ);

?>

<div class="row row-overflow">
	<!-- Page daccueil du plugin -->
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<!-- Boutons de gestion du plugin -->
		<div class="eqLogicThumbnailContainer">
          <div class="cursor eqLogicAction logoPrimary" data-action="add">
            <i class="fas fa-plus-circle"></i>
            <br>
            <span>{{Ajouter une instance}}</span>
          </div>
			<div class="cursor eqLogicAction logoPrimary" data-action="sync">
				<i class="fas fa-sync"></i>
				<br>
				<span>{{Synchronisation automatique}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
  		<legend><i class="fas fa-table"></i> {{Le(s) instance(s)}}</legend>
			<?php
  			if (count($eqLogicsInstances) == 0) {
				echo '<br><div id="div_results" class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucune instance trouvée, cliquez sur "Ajouter une instance"}}</div>';
			} else {
				echo '<div class="eqLogicThumbnailContainer">';            
					foreach ($eqLogicsInstances as $eqLogicsInstance) {
						$opacity = ($eqLogicsInstance->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogicsInstance->getId() . '">';
							echo '<img src="' . $plugin->getPathImgIcon() . '">';
							echo '<br>';
							echo '<span class="name">' . $eqLogicsInstance->getHumanName(true, true) . '</span>';
							echo '<span class="hiddenAsCard displayTableRight hidden">';
							echo ($eqLogicsInstance->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
							echo '</span>';
						echo '</div>';
					}
				echo '</div>';
            }
			?>
                       
		<legend><i class="fas fa-table"></i> {{Le(s) serveur(s)}}</legend>
		<?php
			//if (count($eqLogics) == 0) {
			//	echo '<br><div id="div_results" class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun serveur trouvé, cliquez sur "Synchroniser automatique"}}</div>';
			//} else {
				// Champ de recherche
				echo '<div class="input-group" style="margin:5px;">';
					echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
					echo '<div class="input-group-btn">';
						echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
						//echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
					echo '</div>';
				echo '</div>';
				// Liste des équipements du plugin
				echo '<div id="div_results"></div>';
				echo '<div class="eqLogicThumbnailContainer">';            
					foreach ($eqLogics as $eqLogic) {
						if(($eqLogic->getConfiguration('type', '') == 'console' && $logLevel != 'debug') || $eqLogic->getConfiguration('type', '') == 'instance')
							continue;
                      	
                      	$instanceId = $eqLogic->getConfiguration('instanceId', '');
                      	$nameInstance = ($eqLogic->getConfiguration('type', '') == 'server') ? eqLogic::byId($instanceId)->getName() : "";
						$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                      		echo '<p class="displayInstance">&nbsp;' . $nameInstance . '</p>';
							echo '<img src="' . $plugin->getPathImgIcon() . '" style="padding-top: 5px">';
							echo '<br>';
							echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
							echo '<span class="hiddenAsCard displayTableRight hidden">';
								echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
							echo '</span>';
						echo '</div>';
					}
				echo '</div>';
			//}
		?>

		
	</div> <!-- /.eqLogicThumbnailDisplay -->

	<!-- Page de présentation de l'équipement -->
	<div class="col-xs-12 eqLogic" style="display: none;">
		<!-- barre de gestion de l'équipement -->
		<div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">
				<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<!-- Onglets -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Serveur}}</a></li>
			<li role="presentation" id="ongletCommandes"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<!-- Onglet de configuration de l'équipement -->
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<!-- Partie gauche de l'onglet "Equipements" -->
				<!-- Paramètres généraux et spécifiques de l'équipement -->
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Nom du serveur}}</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" >{{Objet parent}}</label>
								<div class="col-sm-6">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
											$options = '';
											foreach ((jeeObject::buildTree(null, false)) as $object) {
												$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
											}
											echo $options;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Catégorie}}</label>
								<div class="col-sm-6">
									<?php
										foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
											echo '<label class="checkbox-inline">';
												echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
											echo '</label>';
										}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Options}}</label>
								<div class="col-sm-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
								</div>
							</div>

							<hr />
                            <!-- INSTANCE -->
							<div class="form-group instanceGroup">
								<label class="col-sm-4 control-label" >{{Url de votre pterodactyl}} <sup><i class="fas fa-question-circle tooltips" title="{{Renseignez la racine de votre pterodactyl (ex: https://mypterodactyl.io) sans le / de fin}}"></i></sup></label>
								<div class="col-sm-6">
									<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="pteroRootUrl" />
								</div>
							</div>
							<div class="form-group instanceGroup">
								<label class="col-sm-4 control-label" >{{Clé API pour les requêtes}} <sup><i class="fas fa-question-circle tooltips" title="{{Clé API de l'utilisateur qui fera les requêtes}}"></i></sup></label>
								<div class="col-sm-6">
									<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="apiKey" />
								</div>
							</div>
							<div class="form-group instanceGroup">
								<label class="col-sm-4 control-label" >{{Je suis Admin}} <sup><i class="fas fa-question-circle tooltips" title="{{Si vous avez créé une clé API Application, cochez cette case, plus d'informations dans la documentation du plugin}}"></i></sup></label>
								<div class="col-sm-6">
									<input type="checkbox" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="iAmAdmin" />
								</div>
							</div>
                            <!-- / INSTANCE -->
                            <!-- SERVER -->
							<div class="form-group serverGroup">
								<label class="col-sm-4 control-label" >{{Jeux}} <sup><i class="fas fa-question-circle tooltips" title="{{Information nécessaire pour récupérer le nombre de joueurs en ligne / max}}"></i></sup></label> 
                                	
								<div class="col-sm-6">
									<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="game">
										<option value="aucun">{{Aucun}}</option>
                                        <option value="7d2d">7 Days to Die</option>
                                        <option value="arkse">Ark: Survival Evolved</option>
                                        <option value="arma2">ARMA 2</option>
                                        <option value="arma2oa">ARMA 2: Operation Arrowhead</option>
                                        <option value="arma3">ARMA 3</option>
                                        <option value="arma">ARMA: Armed Assault</option>
                                        <option value="armacwa">ARMA: Cold War Assault</option>
                                        <option value="armar">ARMA: Resistance</option>
                                        <option value="assettocorsa">Assetto Corsa</option>
                                        <option value="barotrauma">Barotrauma</option>
                                        <option value="conanexiles">Conan Exiles</option>
                                        <option value="csgo">Counter Strike : Global Offensive</option>
                                        <option value="dayz">DayZ</option>
                                        <option value="dayzmod">DayZ Mod</option>
                                        <option value="garrysmod">Garry's Mod</option>
                                        <option value="fivem">Grand Theft Auto V – FiveM</option>
                                        <option value="mtasa">Grand Theft Auto: San Andreas - Multi Theft Auto</option>
                                        <option value="mtavc">Grand Theft Auto: Vice City - Multi Theft Auto</option>
                                        <option value="hurtworld">Hurtworld</option>
                                        <option value="insurgencysandstorm">Insurgency: Sandstorm</option>
                                        <option value="killingfloor2">Killing Floor 2</option>
                                        <option value="left4dead">Left 4 Dead</option>
                                        <option value="left4dead2">Left 4 Dead 2</option>
                                        <option value="minecraft">Minecraft</option>
                                        <option value="minecraftext">Minecraft (via API externe)</option>
                                        <option value="minecraftpe">Minecraft: Bedrock Edition</option>
                                        <option value="mordhau">Mordhau</option>
                                        <option value="openarena">OpenArena</option>
                                        <option value="openttd">OpenTTD</option>
                                        <option value="pixark">PixARK</option>
                                        <option value="przomboid">Project Zomboid</option>
                                        <option value="quakelive">Quake Live</option>
                                        <option value="rust">Rust</option>
                                        <option value="squad">Squad</option>
                                        <option value="starbound">Starbound</option>
                                        <option value="svencoop">Sven Coop</option>
                                        <option value="tfc">Team Fortress Classic</option>
                                        <option value="forrest">The Forrest</option>
                                        <option value="towerunite">Tower Unite</option>
                                        <option value="unturned">unturned</option>
                                        <option value="valheim">Valheim</option>

										<option value="other" disabled="disabled">{{Autres à venir, n'hésitez pas à demander sur le forum}}</option>
									</select>
								</div>
							</div>
							<div class="form-group serverGroup">
								<label class="col-sm-4 control-label">{{Affichage Console}} <sup><i class="fas fa-question-circle tooltips" title="{{Permet d'avoir la console en temps réel comme sur le panel pterodactyl}}"></i></sup></label>
								<div class="col-sm-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="displayTileConsole" >{{Activer la tuile sur le dashboard}}</label>
								</div>
							</div>
                            <!-- SERVER -->
						</div>

						<!-- Partie droite de l'onglet "Équipement" -->
						<!-- Affiche un champ de commentaire par défaut mais vous pouvez y mettre ce que vous voulez -->

						<div class="col-lg-6 displayInfosServerOnRightPanel">
							<legend><i class="fas fa-info"></i> {{Informations}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Nom sur le serveur et Node}}</label>
								<div class="col-sm-6">
									<div class="form-group cmdAttr label label-primary" id="namePteroServ" style="font-size : 1em">XXX</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">UUID</label>
								<div class="col-sm-6">
									<div class="form-group cmdAttr label label-primary" id="uuidPteroServ" style="font-size : 1em">XXX</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{IP et Port}}</label>
								<div class="col-sm-6">
									<div class="form-group cmdAttr label label-primary" id="portPteroServ" style="font-size : 1em">XXX</div>
								</div>
							</div>
							<!-- <div class="form-group">
								<label class="col-sm-4 control-label">{{Graphique de performances}}</label>
								<div class="col-sm-6">
									<div class="form-group cmdAttr label label-primary" id="graphPteroServ" style="font-size : 1em">XXX</div>
								</div>
							</div> -->
						</div>
					</fieldset>
				</form>
			</div><!-- /.tabpanel #eqlogictab-->

			<!-- Onglet des commandes de l'équipement -->
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>
				<br><br>
				<div class="table-responsive">
					<table id="table_cmd" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
								<th style="min-width:200px;width:350px;">{{Nom}}</th>
								<th>{{Type}}</th>
								<th style="min-width:260px;">{{Options}}</th>
								<th style="min-width:80px;width:200px;">{{Actions}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div><!-- /.tabpanel #commandtab-->

		</div><!-- /.tab-content -->
	</div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<style>
.displayInstance {
	margin: 0;
    padding: 0;
    color: var(--eqTitle-color) !important;
    font-size: 9pt;
    font-style: italic;
}
</style>

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'pterodactyl', 'js', 'pterodactyl');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>
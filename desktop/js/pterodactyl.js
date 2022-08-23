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

/* Permet la réorganisation des commandes dans l'équipement */
$("#table_cmd").sortable({
	axis: "y",
	cursor: "move",
	items: ".cmd",
	placeholder: "ui-state-highlight",
	tolerance: "intersect",
	forcePlaceholderSize: true
})

/* Fonction permettant l'affichage des commandes dans l'équipement */
function addCmdToTable(_cmd) {
	if (!isset(_cmd)) {
		var _cmd = {configuration: {}}
	}
	if (!isset(_cmd.configuration)) {
		_cmd.configuration = {}
	}

	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">'
	tr += '<td class="hidden-xs">'
	tr += '<span class="cmdAttr" data-l1key="id"></span>'
	tr += '</td>'
	tr += '<td>'
	tr += '<div class="input-group">'
	tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
	tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
	tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
	tr += '</div>'
	tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;" title="{{Commande info liée}}">'
	tr += '<option value="">{{Aucune}}</option>'
	tr += '</select>'
	tr += '</td>'
	tr += '<td>'
	tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
	tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
	tr += '</td>'
	tr += '<td>'
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> '
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> '
	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label> '
	tr += '<div style="margin-top:7px;">'
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
	tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
	tr += '</div>'
	tr += '</td>'
	tr += '<td>'
	if (is_numeric(_cmd.id)) {
		tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
		tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> Tester</a>'
	}
	tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove" title="{{Supprimer la commande}}"></i></td>'
	tr += '</tr>'
	$('#table_cmd tbody').append(tr)
	var tr = $('#table_cmd tbody tr').last()
	jeedom.eqLogic.buildSelectCmd({
		id:  $('.eqLogicAttr[data-l1key=id]').value(),
		filter: {type: 'info'},
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'})
		},
		success: function (result) {
			tr.find('.cmdAttr[data-l1key=value]').append(result)
			tr.setValues(_cmd, '.cmdAttr')
			jeedom.cmd.changeType(tr, init(_cmd.subType))
		}
	})
}



function printEqLogic(_eqLogic) {
    let eqLogic = _eqLogic.id;
  	$('.displayInfosServerOnRightPanel').hide();
  	
  	// Création d'un équiepement, pas de type, donc on créé un champs caché
  	console.log(_eqLogic.configuration.hasOwnProperty('type'));
  	if(!_eqLogic.configuration.hasOwnProperty('type')) {
    	let code = '<input type="hidden" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="type" value="instance">';
        $('.serverGroup').hide();
        $('.instanceGroup').show();
       	$('.form-horizontal').append(code);
      	return;
    }
  
	if(_eqLogic.configuration.type == "instance") {
      $('.serverGroup').hide();
      $('.instanceGroup').show();
    } else if(_eqLogic.configuration.type == "server") {
      $('.instanceGroup').hide();
      $('.serverGroup').show();

      $('#namePteroServ').html(infosPteroServ[eqLogic]['nomnode']);
      $('#uuidPteroServ').html(infosPteroServ[eqLogic]['uuid']);
      $('#portPteroServ').html(infosPteroServ[eqLogic]['ipport']);
      //$('#graphPteroServ').html("ICI graphique des 30 dernières minutes par exemple?");

      $('.displayInfosServerOnRightPanel').show();
    } else {
      $('.serverGroup').hide();
      $('.instanceGroup').hide();
    }

	/*jeedom.cmd.update[_eqLogic] = function(_options) {
		console.log(_eqLogic.id + '=>' + _options.display_value)
		$('.cmd[data-cmd_id=' + _eqLogic.id + '] .form-control[data-key=value]').value(_options.display_value);
	}*/
	// jeedom.cmd.update[cmd_id]
	//console.log("============");
	//console.log(_options);
	/*console.log(_eqLogic);
	let arrayCmd = _eqLogic.cmd;
	let eqName = arrayCmd.find(o => o.logicalId === 'name');
	console.log("2======");
	console.log(eqName.id);
	console.log(jeedom.cmd.cache.byId[eqName.id]);
	$('#namePteroServ').html(_eqLogic.cmd + " sur le node " + _eqLogic.node);
	$('#uuidPteroServ').html(_eqLogic.uuid);
	$('#portPteroServ').html(_eqLogic.ip + ":" + _eqLogic.port);
	$('#graphPteroServ').html("ICI graphique des 30 dernières minutes par exemple?");*/
}

$('.eqLogicAction[data-action=sync]').on('click', function() {
	$('.eqLogicAction span:first').text("{{Synchronisation en cours...}}").css({'color' : 'red'});
	$('.eqLogicAction i:first').css({'color' : 'red'});
	runSync();
});

function runSync() {
	console.log('=== Sync in progress ===');

	$.ajax({
		type: 'POST',
		url: 'plugins/pterodactyl/core/ajax/pterodactyl.ajax.php',
		data: {
			action: 'sync'
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			$('.eqLogicAction span:first').text("{{Synchronisation automatique}}").removeAttr('style');
			$('.eqLogicAction i:first').removeAttr('style');
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			console.log(data);

			if(data.result.new == 0){
				$('#div_results').empty().append("<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Aucun serveur trouvé, vérifiez les paramètres dans la configuration de vos instances.}}</span></center>");
				return;
			} else {
				var plurilizedNewServers = (data.result.new == 1) ? "Nouveau serveur détecté" : "Nouveaux serveurs détectés";
				$('#div_results').empty().append("<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'> " + data.result.new + "{{ " + plurilizedNewServers + "}}</span></center>");
			}


			// create div container for results
			$("#div_results").append('<div class="eqLogicThumbnailContainer" id="newServersDetected" style="position: relative; height: 173px;"></div>');

			var html = '';
			var currentLeft = 0;
			for (var i in data.result.servers) {


				html += '<div class="eqLogicDisplayCard cursor " data-eqlogic_id="' + data.result.servers[i].eqlogic + '" style="position: absolute; left: ' + currentLeft + 'px; top: 0px;"">';
				html += '<a href="index.php?v=d&m=pterodactyl&p=pterodactyl&id=' + data.result.servers[i].eqlogic + '">';
				html += '<img src="plugins/pterodactyl/plugin_info/pterodactyl_icon.png"><br>';
				html += '<span class="name">';
				html += '<span class="label labelObjectHuman" style="text-shadow : none;">Aucun</span><br>';
				html += '<strong>' + data.result.servers[i].name + '</strong>';
				html += '</span>';
				html += '</a>';
				html += '</div>';
				currentLeft += 130;
			}

			$('#newServersDetected').append(html);
		},
		done: function(data) {
			console.log('=== Pterodactyl Sync finished ===');
		}
	});


}
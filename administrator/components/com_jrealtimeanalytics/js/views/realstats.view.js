/**
 * View della JS APP per la visualizzazione dati 
 * 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage js
 * @subpackage views
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 or later, see license.txt
 */
(function($) {
	$.jfbcRealstatsView =  function(selectors) {
		/**
		 * Array dei placeholder dove saranno renderizzati i grafici
		 * 
		 * @access private
		 * @var Object
		 */
		var chartsDOMElems = selectors;
		
		/**
		 * URL polling endpoint
		 * 
		 * @access private
		 * @var Object
		 */
		var chartsData;
		
		/**
		 * Configurazione grafici gestibili da parametri per usi futuri e requisiti di bassa priorita 
		 * 
		 * @access private
		 * @var Object
		 */
		var chartsConfig;

		/**
		 * Renderizza i graph and text elems on page a partire dai data forniti
		 * 
		 * @access public
		 * @param Object data
		 * @return void
		 */
		this.renderCharts = function(data) {
			// Init config graphs
			chartsConfig = {};
			
			// Assign injected data
			chartsData = data;
			
			// Creazione grafici iniziale con native ajax model call
			if(!chartsData) {
				// Create Pie chart
				var pieChart = $(chartsDOMElems.piePlaceholder).kendoChart({
					theme: "default",
					dataSource: {
						transport: {
							read: {
								url: jfbcGlobalStatsEndpoint + '?time=' + (new Date().getTime()) + '&pie=1&init=1',
								dataType: "json"
							}
						} 
					},
					title: {
						text: jfbcPiegraphTitle
					},
					legend: {
						position: "top"
					}, 
					seriesDefaults: {
						labels: {
							template: "#= kendo.format('{0:P}', percentage)#",
							visible: true
						}
					},
					series: [{
						type: "pie",
						field: "value",
						categoryField: "source"
					}],
					tooltip: {
						visible: true,
						template: "${ category }-${ value } utenti,#=kendo.format('{0:P}',percentage)#"
					},
					transitions: true,
					chartArea : {
						width: 450,
						height: 400
					}
					
				});
				
				//Create Bar chart
				var barChart = $(chartsDOMElems.barPlaceholder).kendoChart({
					theme: $(document).data("kendoSkin") || "default",
					dataSource: {
						transport: {
							read: {
								url: jfbcGlobalStatsEndpoint + '?time=' + (new Date().getTime()) + '&init=1',
								dataType: "json"
							}
						},
						sort: {
							field: "year",
							dir: "asc"
						}
					},
					title: {
						text: jfbcBargraphTitle
					},
					legend: {
						position: "top"
					},
					seriesDefaults: {
						type: "column",
						labels: {
							visible: true,
							format: "{0:N0} " + jfbcUsers
						}
					},
					series:
						[{
							field: "value",
							name: jfbcUsers
						},],
						categoryAxis: {
							field: "source",
							labels: {
								rotation: -25
							}
						},
						valueAxis: {
							labels: {
								format: "{0:N0}"
							} 
						},
						tooltip: {
							visible: true,
							format: "{0:N0} " + jfbcUsers
						},
						transitions: true,
						chartArea : {
							width: 450,
							height: 400
						}
				});   
				
				// Rimozione transitions per refresh asincroni futuri
				setTimeout(function() {
	            	$(pieChart).data("kendoChart").options.transitions = false;
	             	$(barChart).data("kendoChart").options.transitions = false;
				}, 500); 
					
			} else { // Refresh Charts Data
				var workingCopy = chartsData.slice(0);
				// Refresh bar
				workingCopy.splice(0,2);
				$(chartsDOMElems.barPlaceholder).data("kendoChart").dataSource.data(workingCopy);  
				$(chartsDOMElems.barPlaceholder).data("kendoChart").refresh(); 
				
				workingCopy = chartsData.slice(0);
				// Refresh pie
				// Unsetting del primo array object corrispondente al totale utenti
				workingCopy.splice(0,3); 
				$(chartsDOMElems.piePlaceholder).data("kendoChart").dataSource.data(workingCopy);  
            	$(chartsDOMElems.piePlaceholder).data("kendoChart").refresh(); 
			}
		};
		
		/**
		 * Formatta in formato testuale i dati statistici recuperati
		 * Esplica un rePaint completo sull'onPaint event quando i dati sono cambiati
		 * 
		 * @access public
		 * @param Object data
		 * @return void
		 */
		this.renderTextData = function(data) {
			// Get container reference
			var textDataContainer = $(chartsDOMElems.textPlaceHolder); 
			
			// Clear blackboard
			$(textDataContainer).children().remove();
			
			// Start elements appending title
			$(textDataContainer).append('<div class="texttitle">' + jfbcTextStatsTitle + '</div>');
			
			// Start elements appending data
			if(typeof(data) === 'object') {
				$(textDataContainer).append('<div id="box"></div><div class="datarow">' + data[2]['source'] + ':<span class="datarow_value">' + data[2]['value'] + '</span></div>');
				$(textDataContainer).append('<div id="box"></div><div class="datarow">' + data[3]['source'] + ':<span class="datarow_value">' + data[3]['value'] + '</span></div>');
				$(textDataContainer).append('<div id="box"></div><div class="datarow">' + data[4]['source'] + ':<span class="datarow_value">' + data[4]['value'] + '</span></div>');
			} 
		};
		
		/**
		 * Formatta in formato testuale i dati statistici recuperati per la lista utenti
		 * Esplica un rePaint completo sull'onPaint event quando i dati sono cambiati
		 * 
		 * @access public
		 * @param Object data
		 * @return void
		 */
		this.renderUserlistStats = function(data) {
			// Users list stats rendering
			// Get container reference
			var userslistDataContainer = $(chartsDOMElems.usersPlaceHolder); 
			
			// Clear blackboard
			$(userslistDataContainer).children().remove();
			
			// Start elements appending title
			$(userslistDataContainer).append('<div class="texttitle">' + jfbcUsersStatsTitle + '</div>');
			
			// Table titles
			var titleName = '<span class="little">' + jfbcTitleName + '</span>';
			var titleUsername = '<span class="little">' + jfbcTitleUsername + '</span>';
			var titleType = '<span class="little">' + jfbcTitleType + '</span>';
			var titleTime = '<span class="little fixed">' + jfbcTitleTime + '</span>';
			var titleNowpage = '<span class="large">' + jfbcTitleNowpage + '</span>'; 
			$(userslistDataContainer).append('<div class="titlerow">' + titleName + titleUsername + titleType + titleTime + titleNowpage + '</div>');
			
			// Start elements appending data
			if(data) {
				if(data[0].constructor == Array && data[0].length) {
					$.each(data[0], function (k, item){
						// Si stabilisce se l'url ha una query string
						var separator = item.nowpage.match(/\?.+/) ? '&' : '?';
						if(!item.name) {
							if(item.current_name) {
								item.name = item.current_name;
							} else {
								item.name = '';
							}
						}
						if(!item.usertype) {
							item.usertype = '';
						} 
						var name = '<span class="little">' + item.name + '</span>';
						var username = '<span class="little">' + item.username + '</span>';
						var type = '<span class="little">' + item.usertype + '</span>';
						var time = '<span class="little fixed">' + item.lastupdatetime + '</span>';
						var nowpage = '<span class="large"><a class="preview" title="'+ item.nowpage +'" href="' + item.nowpage + separator + 'notrack=1">' + item.nowpage + '</a></span>';
						
						$(userslistDataContainer).append('<div class="userdatarow">' + name + username + type + time + nowpage + '</div>');
					});
				} 
			} 
		};
		
		/**
		 * Formatta in formato testuale i dati statistici recuperati per le singole pagine
		 * 
		 * @access public
		 * @param Object data
		 * @return void
		 */
		this.renderPerPagesStats = function(data) {
			// Users list stats rendering
			// Get container reference
			var perpageStatsDataContainer = $(chartsDOMElems.perpagePlaceHolder); 
			
			// Clear blackboard
			$(perpageStatsDataContainer).children().remove();
			
			// Start elements appending title
			$(perpageStatsDataContainer).append('<div class="texttitle">' + jfbcPerpageStatsTitle + '</div>');
			
			// Table titles
			var titlePageName = '<span class="large">' + jfbcTitleNowpage + '</span>';
			var titleNumUsers = '<span class="little fixed">' + jfbcTitleNumUsers + '</span>';
			var titleLastVisit = '<span class="little fixed">' + jfbcTitleLastVisit + '</span>';
			$(perpageStatsDataContainer).append('<div class="titlerow">' + titlePageName + titleNumUsers + titleLastVisit + '</div>');
			
			// Start elements appending data
			if(data) {
				if(data[1].constructor == Array && data[1].length) {
					$.each(data[1], function (k, item){  
						// Si stabilisce se l'url ha una query string
						var separator = item.nowpage.match(/\?.+/) ? '&' : '?';
						
						var nowpage = '<span class="large"><a class="preview" title="' + item.nowpage + '" href="' + item.nowpage + separator + 'notrack=1" target="_blank">' + item.nowpage + '</a></span>';
						var numusers = '<span class="little fixed">' + item.numusers + '</span>';
						var lastvisitTime = '<span class="little fixed">' + item.lastvisit + '</span>';
						
						$(perpageStatsDataContainer).append('<div class="userdatarow">' + nowpage + numusers + lastvisitTime + '</div>');
					});
				} 
			}
		}; 
	};  
})(jQuery);



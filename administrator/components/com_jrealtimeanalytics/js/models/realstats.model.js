/**
 * Model della JS APP per l'ottenimento asincrono dei dati statistici
 * server side
 * 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage js
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 or later, see license.txt
 */
(function($) {
	$.jfbcRealstatsModel = function(selectors) {
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
		 * @var string
		 */ 
		var endPoint = jfbcGlobalStatsEndpoint; 
		
		/**
		 * Polled function asyncronous
		 * 
		 * @access public
		 * @return Object
		 */
		this.doPoll = function() {
			// Refresh endpoint to prevent data caching
			noCacheEndPoint = endPoint + '?time=' + (new Date().getTime());
			
			// Se false non e' necessario modificare il grafico
			var dataReturn = false;
			// Cache old graph state - change only se c'e' stata qualche variazione 
			var oldDataArray = $('#placeholder_chartbar').data("kendoChart").dataSource.data(); 
			// Ajax get data
			$.ajax({
        		type:'GET',
        		async:false,
        		url:noCacheEndPoint,
        		dataType:'json',
        		success: function(originalArray) { 
        			var newDataArray = originalArray.slice(0);
        			var matchingDataArray = originalArray.splice(1);
        			dataReturn = originalArray;
        			var notChanged = !!($.stringifyJSON(matchingDataArray) === $.stringifyJSON(oldDataArray)); 
        			if(!notChanged) {
        				dataReturn = newDataArray;
                	} 
        		}
        	}); 
			return dataReturn;
		};
	};
})(jQuery);
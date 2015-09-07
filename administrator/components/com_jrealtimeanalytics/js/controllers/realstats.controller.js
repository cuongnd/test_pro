/**
 * Controller della JS APP in IIFE execution polling dispatch
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage js
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 or later, see license.txt
 */
(function($) {
	$.jfbcRealstatsController = function() {
		/**
		 * Array dei placeholder dove saranno renderizzati i grafici
		 * 
		 * @access private
		 * @var Object
		 */
		var chartsDOMElems = {	textPlaceHolder:'#placeholder_textstats', 
								piePlaceholder:'#placeholder_chartpie', 
								barPlaceholder:'#placeholder_chartbar', 
								usersPlaceHolder:'#placeholder_text',
								perpagePlaceHolder:'#placeholder_perpage'};
		
		/**
		 * Main model Object 
		 * @access private
		 * @var Object
		 */
		var model;
		
		/**
		 * Main view Object
		 * @access private
		 * @var Object
		 */
		var view;
		
		/**
		 * Polling interval
		 * @access private
		 * @var int
		 */
		var refreshInterval = jfbcIntervalRealStats * 1000;

		/**
		 * Istanzia la model
		 * @access private 
		 * @return Object
		 */
		function getModel() {
			modelInstance = new $.jfbcRealstatsModel(chartsDOMElems);
			return modelInstance;
		};

		/**
		 * Istanzia la view
		 * @access private 
		 * @return Object
		 */
		function getView() {
			viewInstance  = new $.jfbcRealstatsView(chartsDOMElems);
			return viewInstance;
		};

		/**
		 * @access public
		 */
		(function initApp() { 
			// Istanziazione MVC
			model = getModel();
			view = getView();
			
			// initial view render charts
			setTimeout(function() {
				// Initialize the chart with a delay to make sure
				// the initial animation is visible
				view.renderCharts(false);
				 
				// Initialize text data
				var initialData = model.doPoll();
				view.renderTextData(initialData);
				
				// Si aggiorna sempre il realtime delle userlist stats
				view.renderUserlistStats(initialData);
				
				// Si aggiorna sempre il realtime delle userlist stats
				view.renderPerPagesStats(initialData);
             }, 100); 
              
			setTimeout(function() { 
				// Hiddenify dei campi text con NaN
				$('text').filter(function(index){  
					return $(this).text() == 'NaN';
				}).hide();
				
				// Fancybox page preview degli utenti
				$(".preview").fancybox({
					'width'				: '85%',
					'height'			: '90%',
					'autoScale'			: false, 
					'transitionOut'		: 'none',
					'type'				: 'iframe'
				});
			}, 101);
			
			// Start controller model polling realtime data
			setInterval(function refreshChart() {  
				var dataState = model.doPoll();
				// Se i dati sono cambiati view refresh
				if(dataState.length > 1) {
					view.renderTextData(dataState);
					view.renderCharts(dataState);
					
					// Hiddenify dei campi text con NaN
					$('text').filter(function(index){  
						return $(this).text() == 'NaN';
					}).hide();
				}
				// Si aggiorna sempre il realtime delle userlist stats
				view.renderUserlistStats(dataState);
				
				// Si aggiorna sempre il realtime delle userlist stats
				view.renderPerPagesStats(dataState);
				
				// Fancybox page preview degli utenti
				$(".preview").fancybox({
					'width'				: '85%',
					'height'			: '90%',
					'autoScale'			: false, 
					'transitionOut'		: 'none',
					'type'				: 'iframe' 
				});
			}, refreshInterval);
			
		}).call(this);
	}; 
})(jQuery);
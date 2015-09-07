/*
 *
 * Copyright (c) 2006-2009 Sam Collett (http://www.texotela.co.uk)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version 2.2.4
 * Demo: http://www.texotela.co.uk/code/jquery/select/
 *
 * $LastChangedDate: 2009-02-08 00:28:12 +0000 (Sun, 08 Feb 2009) $ 
 * $Rev: 6185 $
 *
 */

/*
 * 
 * Developed by Nick Busey (http://nickbusey.com/) Dual licensed under the MIT
 * (http://www.opensource.org/licenses/mit-license.php) and GPL
 * (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * 
 * Version 2.0.0 Demo: http://nickabusey.com/jquery-date-select-boxes-plugin/
 * 
 */
(function($) {
	 var months = {
			    "short": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			    "long": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"] },
			      todayDate = new Date(),
			      todayYear = todayDate.getFullYear(),
			      todayMonth = todayDate.getMonth() + 1,
			      todayDay = todayDate.getDate();
	$.fn.dateSelectBoxes = function(options) {
		var defaults = {
			keepLabels : false,
			yearMax : new Date().getFullYear(),
			yearMin : 1900,
			generateOptions : false,
			monthFormat:'short',
			
			fieldName:'birthdate',
			fieldId:'fieldId',
			hiddenDate:true,
			yearLabel : 'Year',
			monthLabel : 'Month',
			dayLabel : 'Day'

		}

		var settings = $.extend({}, defaults, options);

		if (settings.keepLabels) {
			var dayLabel = settings.dayElement.val();
		}
		var allDays = {};
		for (var ii = 1; ii <= 31; ii++) {
			allDays[ii] = ii;
		}

		if (settings.generateOptions) {
			var years = [];
			if (settings.yearLabel && settings.keepLabels) {
				years.push(settings.yearLabel)
			}
			for (var ii = settings.yearMax; ii >= settings.yearMin; ii--) {
				years.push(ii);
			}
			settings.yearElement.addOption(years, false);

			
			if (settings.monthLabel && settings.keepLabels) {
				$.extend(months, {
					"0" : settings.monthLabel
				});
			}
			settings.monthElement.addOption(months, false);
			if (settings.dayLabel && settings.keepLabels) {
				settings.dayElement.addOption({
					0 : settings.dayLabel
				}, false);
			}
			settings.dayElement.addOption(allDays, false);
		}

		function isLeapYear() {
			var selected = settings.yearElement.selectedValues();
			return (selected === ""
					|| ((selected % 4 === 0) && (selected % 100 !== 0)) || (selected % 400 === 0));
		}
		function updateDays() {
			var selected = settings.dayElement.selectedValues(), days = [], i;
			var hiddenDate;
			settings.dayElement.removeOption(/./);
			var month = parseInt(settings.monthElement.val(), 10);
			if (!month) {
				// Default to 31 days if no month selected.
				month = 1;
			}
			switch (month) {
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				for (ii = 1; ii <= 31; ii++) {
					days[ii] = allDays[ii];
				}
				break;
			case 2:
				var febDays = 0;
				if (isLeapYear()) {
					febDays = 29;
				} else {
					febDays = 28;
				}
				for (ii = 1; ii <= febDays; ii++) {
					days[ii] = allDays[ii];
				}
				break;
			case 4:
			case 6:
			case 9:
			case 11:
				for (ii = 1; ii <= 30; ii++) {
					days[ii] = allDays[ii];
				}
				break;
			}
			if (settings.dayLabel && settings.keepLabels) {
				days[0] = settings.dayLabel;
			}
			
			settings.dayElement.addOption(days, false);
			settings.dayElement.selectOptions(selected);
			settings.dayElement.val(selected);
			
			var selectedDay = settings.dayElement.val();
			var selectedMonth = settings.monthElement.val();
			var selectedYear = settings.yearElement.val();
			 if ((selectedYear * selectedMonth * selectedDay) != 0) {
	          if (selectedMonth<10) selectedMonth="0"+selectedMonth;
	          if (selectedDay<10) selectedDay="0"+selectedDay;
	          hiddenDate =  selectedDay+ "-" + selectedMonth + "-" + selectedYear;
	          settings.birthElement.val(hiddenDate);
	        
	        }
			 
		}
		settings.yearElement.trigger("liszt:updated");
		settings.dayElement.trigger("liszt:updated");
		settings.monthElement.trigger("liszt:updated");
		settings.dayElement.chosen().change(function() {

			updateDays();
			settings.dayElement.trigger("liszt:updated");
			settings.monthElement.trigger("liszt:updated");
			settings.yearElement.trigger("liszt:updated");
			
		});
		settings.yearElement.chosen().change(function() {

			updateDays();
			settings.dayElement.trigger("liszt:updated");
			settings.monthElement.trigger("liszt:updated");
			settings.yearElement.trigger("liszt:updated");
			
		});
		settings.monthElement.chosen().change(function() {
			
			updateDays();
			settings.dayElement.trigger("liszt:updated");
			settings.monthElement.trigger("liszt:updated");
			settings.yearElement.trigger("liszt:updated");
		});
	};
}(jQuery));

<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.birthday', function($) {

	var module = this;

	EasySocial.require()
	.library( 'ui/datepicker' )
	.done(function($){

		EasySocial.Controller(
			'Field.Birthday',
			{
				defaultOptions:
				{
					required 	: false,
					calendar 	: false,

					format		: null,

					yearfrom	: 1930,

					yearto		: 2013,

					'{field}'	: '[data-field-birthday]',

					'{day}' 	: '[data-field-birthday-day]',
					'{month}' 	: '[data-field-birthday-month]',
					'{year}' 	: '[data-field-birthday-year]',

					'{date}'	: '[data-field-birthday-date]'
				}
			},
			function( self )
			{
				return {
					init : function() {
						self.options.calendar = !!self.field().data('calendar');

						var format = self.field().data('format');

						switch(format) {
							case 2:
								self.options.format = 'mm/dd/yy';
								break;

							case 3:
								self.options.format = 'yy/dd/mm';
								break;

							case 4:
								self.options.format = 'yy/mm/dd';
								break;

							case 1:
							default:
								self.options.format = 'dd/mm/yy';
								break;
						}

						self.options.yearfrom = self.field().data('yearfrom');
						self.options.yearto = self.field().data('yearto');

						if(self.options.calendar) {
							self.date().datepicker({
								changeMonth	 		: true,
								changeYear 			: true,
								// http://bugs.jqueryui.com/ticket/8994
								// This option disables the navigation buttons
								// It is a bug in jQui 1.10.0pre
								// Fixed in jQui 1.10.1
								yearRange			: self.options.yearfrom + ':' + self.options.yearto,
								dateFormat			: self.options.format,
								onChangeMonthYear	: function(year, month, inst) {
									if(inst.currentYear != inst.selectedYear || inst.currentMonth != inst.selectedMonth) {
										$(this).datepicker("setDate", new Date(year, month - 1, inst.selectedDay));
									}
								},
								onSelect			: function() {
									self.validateCalendar();
									$(this).data('datepicker').inline = true;
								},
								onClose: function() {
									$(this).data('datepicker').inline = false;
								}
							});
						}
					},

					'{date} blur': function() {
						self.validateCalendar();
					},

					validateInput : function() {
						self.clearError();

						var day 	= self.day().val(),
							month 	= self.month().val(),
							year 	= self.year().val();

						if(self.options.required) {
							if($.isEmpty(day)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_VALIDATION_PLEASE_ENTER_BIRTHDAY_DAY' , true );?>');
								return false;
							}

							if($.isEmpty(month)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_VALIDATION_PLEASE_ENTER_BIRTHDAY_MONTH' , true );?>');
								return false;
							}

							if($.isEmpty(year)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_VALIDATION_PLEASE_ENTER_BIRTHDAY_YEAR' , true );?>');
								return false;
							}
						}

						if(!$.isEmpty(year) && (year < self.options.yearfrom || year > self.options.yearto)) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_VALIDATION_BIRTHDAY_YEAR_OUT_OF_RANGE' , true );?>');
							return false;
						}

						if((!$.isEmpty(day) && !(day > 0)) || (!$.isEmpty(month) && !(month > 0)) || (!$.isEmpty(year) && !(year > 0))) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_VALIDATION_INVALID_DATE_FORMAT' , true ); ?>');
							return false;
						}

						return true;
					},

					validateCalendar: function() {
						self.clearError();

						if(self.options.required && $.isEmpty(self.date().val())) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_BIRTHDAY_VALIDATION_PLEASE_SELECT_BIRTHDAY' , true );?>');
							return false;
						}

						return true;
					},

					raiseError: function(msg) {
						self.trigger('error', [msg]);
					},

					clearError: function() {
						self.trigger('clear');
					},

					"{self} onSubmit": function(el, event, register) {
						if(self.options.calendar) {
							register.push(self.validateCalendar());
							return;
						}


						register.push(self.validateInput());
						return;
					}
				}
			});

		module.resolve();
	});
});

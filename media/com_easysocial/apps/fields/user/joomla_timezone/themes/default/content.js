<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>

EasySocial.module('field.joomla_timezone', function($) {
	var module = this;

	EasySocial.Controller('Field.Joomla_timezone', {
		defaultOptions: {
			required 		: false,

			'{field}'		: '[data-field-joomla_timezone]',

			'{input}'		: '[data-field-joomla_timezone-input]'
		}
	}, function(self) {
		return {
			init : function() {
			},

			validateInput: function() {
				if(!self.options.required) {
					return true;
				}

				self.clearError();

				var value = self.input().val();

				if(value === 'null' || $.isEmpty(value)) {
					self.raiseError();
					return false;
				}

				return true;
			},

			raiseError: function() {
				self.trigger('error', ['<?php echo JText::_( 'PLG_FIELDS_JOOMLA_TIMEZONE_VALIDATION_SELECT_TIMEZONE' ); ?>']);
			},

			clearError: function() {
				self.trigger('clear');
			},

			'{input} change': function() {
				self.validateInput();
			},

			"{self} onSubmit": function(el, event, register) {
				register.push(self.validateInput());
			}
		}
	});

	module.resolve();
});

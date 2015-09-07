<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.address', function($) {

	var module = this;

	EasySocial.Controller('Field.Address', {
		defaultOptions: {
			required_address1	: false,
			required_address2	: false,
			required_city		: false,
			required_state		: false,
			required_zip		: false,
			required_country	: false,

			"{field}"		: "[data-field-address]",

			"{address1}"	: "[data-field-address-address1]",
			"{address2}"	: "[data-field-address-address2]",
			"{city}"		: "[data-field-address-city]",
			"{state}"		: "[data-field-address-state]",
			"{country}"		: "[data-field-address-country]",
			"{zip}"			: "[data-field-address-zip]",

			'{required}'	: '[data-required]'
		}
	}, function(self) {
		return {
			init : function() {
				var required = self.field().data('address-required');

				self.options.required_address1 = required.address1 !== undefined ? required.address1 : self.options.required_address1;
				self.options.required_address2 = required.address2 !== undefined ? required.address2 : self.options.required_address2;
				self.options.required_city = required.city !== undefined ? required.city : self.options.required_city;
				self.options.required_state = required.state !== undefined ? required.state : self.options.required_state;
				self.options.required_zip = required.zip !== undefined ? required.zip : self.options.required_zip;
				self.options.required_country = required.country !== undefined ? required.country : self.options.required_country;
			},

			validateInput : function() {
				self.clearError();

				var address1 	= self.address1().val(),
					address2	= self.address2().val(),
					city 		= self.city().val(),
					state 		= self.state().val(),
					country 	= self.country().val(),
					zip			= self.zip().val();

				if($.isEmpty(address1) && self.options.required_address1)
				{
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_ADDRESS1' , true ); ?>');
					return false;
				}

				if($.isEmpty(address2) && self.options.required_address2)
				{
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_ADDRESS2' , true ); ?>');
					return false;
				}

				if($.isEmpty(city) && self.options.required_city)
				{
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_CITY' , true ); ?>');
					return false;
				}

				if($.isEmpty(state) && self.options.required_state)
				{
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_STATE' , true ); ?>');
					return false;
				}

				if($.isEmpty(zip) && self.options.required_zip)
				{
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_ZIP' , true ); ?>');
					return false;
				}

				if($.isEmpty(country) && self.options.required_country)
				{
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_COUNTRY' , true ); ?>');
					return false;
				}

				return true;
			},

			'{address1}, {address2}, {zip}, {city}, {state} blur': function() {
				self.validateInput();
			},

			'{country} change': function() {
				self.validateInput();
			},

			raiseError: function(msg) {
				self.trigger('error', [msg]);
			},

			clearError: function() {
				self.trigger('clear');
			},

			"{self} onSubmit" : function(el, event, register) {
				register.push(self.validateInput());
			},

			"{self} onConfigChange": function(el, event, name, value) {
				var requires = ['required_address1', 'required_address2', 'required_city', 'required_zip', 'required_state', 'required_country'];

				if($.inArray(name, requires) >= 0) {
					self.options[name] = !!value;
				}

				self.required().hide();

				$.each(requires, function(i, t) {
					if(self.options[t]) {
						self.required().show();
						return false;
					}
				});
			}
		}
	});

	module.resolve();
});

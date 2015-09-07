<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.joomla_fullname', function($) {
	var module = this;

	EasySocial.Controller('Field.Joomla_fullname', {
		defaultOptions: {
			nameFormat 		: 1,

			required 		: true,

			'{field}'		: '[data-field-joomla_fullname]',

			'{firstName}'	: '[data-field-jname-first]',
			'{middleName}'	: '[data-field-jname-middle]',
			'{lastName}'	: '[data-field-jname-last]',
			'{name}'		: '[data-field-jname-name]'
		}
	}, function(self) {
		return {
			init : function()
			{
				self.options.nameFormat = self.field().data('name-format');
			},

			validateInput : function()
			{
				if(!self.options.required) {
					return true;
				}

				self.clearError();

				// Name format
				// 1 - first , middle, last
				// 2 - last , middle , first
				// 3 - Single name

				if(self.options.nameFormat == 3) {
					if($.isEmpty(self.name().val())) {
						self.raiseError();
						return false;
					}

					return true;
				}

				if($.isEmpty(self.firstName().val())) {
					self.raiseError();
					return false;
				}

				return true;
			},

			raiseError: function() {
				self.trigger('error', '<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_VALIDATION_EMPTY_NAME' ); ?>');
			},

			clearError: function() {
				self.trigger('clear');
			},

			"{firstName}, {name} blur" : function(el, event) {
				self.validateInput();
			},

			"{self} onError": function(el, event, type, field) {
				self.raiseError();
			},

			"{self} onSubmit" : function(el, event, register) {
				register.push(self.validateInput());

				return;
			}
		}
	});

	module.resolve();
})

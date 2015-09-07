<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.terms', function($) {
	var module = this;

	EasySocial.Controller('Field.Terms',
	{
		defaultOptions:
		{
			required 		: false,

			'{textbox}'		: '[data-field-terms-textbox]',
			'{checkbox}'	: '[data-field-terms-checkbox]'
		}
	},
	function(self)
	{
		return {
			init : function() {
			},

			validateInput: function() {
				self.clearError();

				if(self.options.required && !self.checkbox().is(':checked'))
				{
					self.raiseError();
					return false;
				}

				return true;
			},

			raiseError: function() {
				self.trigger('error', ['<?php echo JText::_( 'PLG_FIELDS_TERMS_VALIDATION_REQUIRED' ); ?>']);
			},

			clearError: function() {
				self.trigger('clear');
			},

			'{self} onSubmit': function(el, event, register) {
				register.push(self.validateInput());
				return;
			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'message':
						self.textbox().val(value);
						break;
				}
			}
		}
	});

	module.resolve();
});

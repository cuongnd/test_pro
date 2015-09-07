<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.email', function($) {
	var module = this;

	EasySocial.Controller(
		'Field.Email',
		{
			defaultOptions:
			{
				required		: false,

				"{field}"		: "[data-field-email]",

				"{input}"		: "[data-field-email-input]"
			}
		},
		function( self )
		{
			return {
				init: function() {
				},

				validateInput: function() {
					var value 	= self.input().val();

					if($.isEmpty(value)) {
						self.raiseError('<?php echo JText::_('PLG_FIELDS_EMAIL_VALIDATION_REQUIRED' , true );?>');
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

					if(!self.options.required) {
						register.push(true);
						return;
					}

					register.push(self.validateInput());

					return;
				}
			}
		});

	module.resolve();
});

<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.checkbox', function($) {
	var module = this;

	EasySocial.Controller(
		'Field.Checkbox',
		{
			defaultOptions:
			{
				required 		: false,
				"{item}"		: "[data-field-checkbox-item]"
			}
		},
		function( self )
		{
			return {
				init : function() {
					window.test = self;
				},

				validateInput : function() {
					self.clearError();

					if(self.options.required && self.item(':checked').length == 0) {
						self.raiseError();
						return false;
					}

					return true;
				},

				raiseError: function() {
					self.trigger('error', ['<?php echo JText::_( 'PLG_FIELDS_CHECKBOX_CHECK_AT_LEAST_ONE_ITEM' ); ?>']);
				},

				clearError: function() {
					self.trigger('clear');
				},

				"{self} onSubmit": function(el, event, register) {
					register.push(self.validateInput());
					return;
				}
			}
		});

	module.resolve();
});

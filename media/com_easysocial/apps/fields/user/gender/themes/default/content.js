<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.gender', function($) {
	var module = this;

	EasySocial.Controller(
		'Field.Gender',
		{
			defaultOptions:
			{
				required 		: false,

				"{field}"		: "[data-field-gender]",

				"{input}"		: "[data-field-gender-input]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
				},

				validateInput: function() {
					if(!self.options.required) {
						return true;
					}

					self.clearError();

					var value = self.input().val();

					if(value === 'null' || $.isEmpty(value))
					{
						self.raiseError();
						return false;
					}

					return true;
				},

				raiseError: function() {
					self.trigger('error', ['<?php echo JText::_( 'PLG_FIELDS_GENDER_VALIDATION_GENDER_REQUIRED' ); ?>']);
				},

				clearError: function() {
					self.trigger('clear');
				},

				"{input} change": function(el, event) {
					self.validateInput();
				},

				"{self} onSubmit": function(el, event, register) {
					register.push(self.validateInput());
				}
			}
		});

	module.resolve();
});

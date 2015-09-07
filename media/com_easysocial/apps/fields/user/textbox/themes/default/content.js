<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.textbox', function($) {
	var module = this;

	EasySocial.Controller('Field.Textbox', {
		defaultOptions: {
			required: false,

			min: 0,
			max: 0,

			'{field}': '[data-field-textbox]',

			'{input}': '[data-field-textbox-input]',

			'{notice}': '[data-check-notice]'
		}
	}, function(self) {
		return {
			init: function() {
				self.options.min = self.field().data('min');
				self.options.max = self.field().data('max');
			},

			'{input} keyup': function()
			{
				self.validateInput();
			},

			'{input} blur': function()
			{
				self.validateInput();
			},

			validateInput: function()
			{
				self.clearError();

				var value = self.input().val();

				if(self.options.required && $.isEmpty(value)) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_REQUIRED', true ); ?>');
					return false;
				}

				if(!$.isEmpty(value) && self.options.min > 0 && value.length < self.options.min) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_SHORT', true ); ?>');
					return false;
				}

				if(self.options.max > 0 && value.length > self.options.max) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_LONG', true ); ?>');
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

			'{self} onError': function(el, ev, type) {
				if(type === 'required') {
					self.notice().html('<?php echo JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_REQUIRED', true ); ?>');
				}

				if(type === 'validate') {
					self.notice().html('<?php echo JText::_( 'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_INAVLID_FORMAT', true ); ?>');
				}
			},

			'{self} onSubmit': function(el, ev, register) {
				register.push(self.validateInput());
			}
		}
	})

	module.resolve();
});

<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>

EasySocial.module('field.textarea', function($) {
	var module = this;

	EasySocial.Controller('Field.Textarea', {
		defaultOptions: {
			required 		: false,

			min: 0,
			max: 0,

			'{field}'		: '[data-field-textarea]',

			'{input}'		: '[data-field-textarea-input]'
		}
	}, function( self ) {
		return {
			init : function() {
				self.options.min = self.field().data('min');
				self.options.max = self.field().data('max');
			},

			validateInput : function()
			{
				self.clearError();

				var val 	= self.input().val();

				if(self.options.required && $.isEmpty(val)) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_TEXTAREA_VALIDATION_INPUT_REQUIRED', true );?>');
					return false;
				}

				if(self.options.min > 0 && val.length < self.options.min) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_TEXTAREA_VALIDATION_INPUT_TOO_SHORT', true );?>');
					return false;
				}

				if(self.options.max > 0 && val.length > self.options.max) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_TEXTAREA_VALIDATION_INPUT_TOO_LONG', true );?>');
					return false;
				}

				return true;
			},

			'{self} onError': function(el, event, type) {
				if(type ==='required' ) {
					self.notice().html('<?php echo JText::_( 'PLG_FIELDS_TEXTAREA_VALIDATION_INPUT_REQUIRED', true ); ?>');
				}
			},

			raiseError: function(msg) {
				self.trigger('error', [msg]);
			},

			clearError: function() {
				self.trigger('clear');
			},

			'{self} onSubmit': function(el, event, register) {
				register.push(self.validateInput());
			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'default':
						self.input().val(value);
						break;

					case 'placeholder':
						self.input().attr('placeholder', value);
						break;

					case 'readonly':
						if(value) {
							self.input().attr('readonly', 'readonly');
						} else {
							self.input().removeAttr('readonly');
						}
						break;
				}
			}
		}
	});

	module.resolve();
});

<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.url', function($) {
	var module = this;

	EasySocial.Controller('Field.Url', {
		defaultOptions: {
			required 		: false,

			'{input}' 		: '[data-field-url-input]'
		}
	}, function( self ) {
		return {
			init: function() {
			},

			'{input} blur': function() {
				self.validateInput();
			},

			validateInput: function() {
				self.clearError();

				var value = self.input().val();

				if(self.options.required && $.isEmpty(value)) {
					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_URL_VALIDATION_EMPTY_URL' , true );?>');
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

			'{self} onError': function(el, event, type, field) {
				self.raiseError('<?php echo JText::_( 'PLG_FIELDS_URL_VALIDATION_EMPTY_URL' , true );?>');
			}
		}
	});

	module.resolve();
});

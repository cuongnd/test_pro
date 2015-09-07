<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>

EasySocial.module('field.joomla_email', function($) {
	var module = this;

	EasySocial.Controller('Field.Joomla_email', {
		defaultOptions: {
			required	: true,

			id			: null,

			userid		: null,

			'{input}'	: '[data-field-email-input]'
		}
	}, function(self) {
		return {
			init: function() {
			},

			'{input} blur': function(el, ev) {
				self.validateInput();
			},

			validateInput: function() {
				self.clearError();

				var value = self.input().val();

				if($.isEmpty(value)) {
					if(!self.options.required) {
						return true;
					}

					self.raiseError('<?php echo JText::_( 'PLG_FIELDS_JOOMLA_EMAIL_VALIDATION_REQUIRED' ); ?>');
					return false;
				}

				return self.checkInput()
					.done(function() {

					})
					.fail(function(msg) {
						self.raiseError(msg);
					});
			},

			checkInput: function() {
				return EasySocial.ajax('fields/user/joomla_email/isValid', {
					id: self.options.id,
					userid: self.options.userid,
					email: self.input().val()
				});
			},

			raiseError: function(msg) {
				self.trigger('error', [msg]);
			},

			clearError: function() {
				self.trigger('clear');
			},

			'{self} onSubmit': function(el, ev, register) {
				register.push(self.validateInput());
			}
		}
	});

	module.resolve();
});

<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>

EasySocial.module('field.joomla_username', function($) {
	var module = this;

	EasySocial.Controller('Field.Joomla_username', {
		defaultOptions: {
			id: null,

			userid: null,

			'{checkUsernameButton}': '[data-username-check]',

			'{input}': '[data-username-input]',

			'{available}': '[data-username-available]'
		}
	}, function(self) {
		return {
			state: false,

			init: function() {
			},

			'{checkUsernameButton} click': function() {
				self.checkUsername();
			},

			'{input} blur': function() {
				self.checkUsername();
			},

			'{input} keyup': $.debounce(function() {
				self.checkUsername();
			}, 500),

			checkUsername: function() {
				self.clearError();

				self.state = $.Deferred();

				self.checkUsernameButton().addClass('btn-loading');

				var username = self.input().val();

				EasySocial.ajax('fields/user/joomla_username/isValid', {
					id: self.options.id,
					userid: self.options.userid,
					username: username
				}).done(function(msg) {

					self.checkUsernameButton().removeClass('btn-loading');

					self.available().show();

					self.state.resolve();
				}).fail(function(msg) {

					self.raiseError(msg);

					self.checkUsernameButton().removeClass('btn-loading');

					self.available().hide();

					self.state.reject();
				});

				return self.state;
			},

			raiseError: function(msg) {
				self.trigger('error', [msg]);
			},

			clearError: function() {
				self.trigger('clear');
			},

			'{self} onSubmit': function(el, ev, register) {
				register.push(self.checkUsername());
			}
		}
	});

	module.resolve();
});

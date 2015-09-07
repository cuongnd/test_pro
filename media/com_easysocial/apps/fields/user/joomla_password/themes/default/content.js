<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>

EasySocial.module('field.joomla_password', function($) {
	var module = this;

	EasySocial.require()
	.library( 'passwordstrength' )
	.done( function($){

		EasySocial.Controller(
			'Field.Joomla_password',
			{
				defaultOptions:
				{
					mode				: null,
					required 			: false,
					passwordStrength	: false,
					reconfirmPassword	: false,

					min	: 4,

					'{field}'		: '[data-field-joomla_password]',

					'{input}'		: '[data-field-password-input]',
					'{reconfirm}'	: '[data-field-password-confirm]',

					'{strength}'	: '[data-field-password-strength]',

					'{reconfirmNotice}'	: '[data-reconfirmPassword-failed]'
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.options.passwordStrength = !!self.field().data('password-strength');

						self.options.reconfirmPassword = !!self.field().data('reconfirm-password');

						self.options.min = self.field().data('min');

						if(self.options.passwordStrength) {
							self.initPasswordStrength();
						}
					},

					'{input} keyup': function() {
						self.validatePassword();
					},

					'{input} blur': function() {
						self.validatePassword();
					},

					'{reconfirm} keyup': function() {
						self.validatePassword();
					},

					'{reconfirm} blur': function() {
						self.validatePassword();
					},

					validatePassword: function()
					{
						self.clearError();

						var input = self.input().val(),
							reconfirm = self.reconfirm().val();

						if(self.options.reconfirmPassword && (!$.isEmpty(input) || !$.isEmpty(reconfirm)))
						{
							if($.isEmpty(input)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_EMPTY_PASSWORD' ); ?>');
								return false;
							}

							if($.isEmpty(reconfirm)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_EMPTY_RECONFIRM_PASSWORD' ); ?>');
								return false;
							}

							if(input !== reconfirm) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_NOT_MATCHING' ); ?>');
								return false;
							}
						}

						if($.isEmpty(input) && self.options.mode !== 'edit' && self.options.mode !== 'adminedit') {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_NOT_MATCHING' ); ?>');
							return false;
						}

						if(self.options.mode !== 'edit' && self.options.mode !== 'adminedit' && self.options.min > 0 && input.length < self.options.min) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_TOO_SHORT' ); ?>');
							return false;
						}

						return true;
					},

					initPasswordStrength: function() {
						self.input().password_strength({
							container: self.strength.selector,
							minLength: self.options.min,
							texts: {
								1: '<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_STRENGTH_VERY_WEAK' , true );?>',
								2: '<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_STRENGTH_WEAK' , true );?>',
								3: '<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_STRENGTH_NORMAL' , true );?>',
								4: '<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_STRENGTH_STRONG' , true );?>',
								5: '<?php echo JText::_( 'PLG_FIELDS_JOOMLA_PASSWORD_STRENGTH_VERY_STRONG' , true );?>'
							},
							onCheck: function(level) {
								if(level <= 1) {
									self.strength()
										.removeClass('text-warning')
										.removeClass('text-success')
										.addClass('text-error small help-inline');
								}

								if(level > 1 && level <= 3) {
									self.strength()
										.removeClass('text-error')
										.removeClass('text-success')
										.addClass('text-warning small help-inline');
								}

								if(level >= 4) {
									self.strength()
										.removeClass('text-error')
										.removeClass('text-warning')
										.addClass('text-success small help-inline');
								}
							}
						})
					},

					raiseError: function(msg) {
						self.trigger('error', [msg]);
					},

					clearError: function() {
						self.trigger('clear');
					},

					"{self} onSubmit": function(el, event, register) {
						register.push(self.validatePassword());
					}
				}
			});

		module.resolve();
	});
});

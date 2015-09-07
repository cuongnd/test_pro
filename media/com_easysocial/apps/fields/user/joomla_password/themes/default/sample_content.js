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

	EasySocial.Controller('Field.Joomla_password', {
		defaultOptions: {
			'{confirmPassword}'		: '[data-password-confirm]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'reconfirm_password':
						self.confirmPassword().toggle(value);
					break;
				}
			}
		}
	});

	module.resolve();
});

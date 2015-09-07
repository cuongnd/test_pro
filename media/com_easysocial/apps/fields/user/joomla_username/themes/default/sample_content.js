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
			'{checkUsername}'		: '[data-check-username]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'check_username':
						self.checkUsername().toggle(!!value);
					break;
				}
			}
		}
	});

	module.resolve();
});

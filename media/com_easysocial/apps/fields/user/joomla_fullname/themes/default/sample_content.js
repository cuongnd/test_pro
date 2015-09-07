<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.joomla_fullname', function($) {

	var module = this;

	EasySocial.Controller('Field.Joomla_fullname', {
		defaultOptions: {
			'{fullnameFormat}'		: '[data-fullname-format]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'format':
						self.switchFormat(value);
					break;
				}
			},

			switchFormat: function(value) {
				self.fullnameFormat().hide();

				self.fullnameFormat().eq(value - 1).show();
			}
		}
	});

	module.resolve();
});

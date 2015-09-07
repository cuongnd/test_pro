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
			'{input}'			: '[data-input]',

			'min'					: '',
			'max'					: '',
			'regex_validate'		: false,
			'regex_format'			: '',
			'regex_modifier'		: ''
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'placeholder':
						self.input().attr('placeholder', value);
					break;

					case 'default':
						self.input().val(value);
					break;

					case 'readonly':
						if(value) {
							self.input().attr('disabled', 'disabled');
						} else {
							self.input().removeAttr('disabled');
						}
						break;
					break;
				}
			}
		}
	});

	module.resolve();
});

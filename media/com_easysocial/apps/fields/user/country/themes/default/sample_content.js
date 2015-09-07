<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.country', function($) {
	var module = this;

	EasySocial.Controller('Field.Country', {
		defaultOptions: {
			'{inputGeneral}'		: '[data-country-select]',

			'{inputTextboxlist}'	: '[data-country-select-textboxlist]',

			'{inputMultilist}'		: '[data-country-select-multilist]',

			'{inputCheckbox}'		: '[data-country-select-checkbox]',

			'{inputDropdown}'		: '[data-country-select-dropdown]',

			'{maxMessage}'			: '[data-country-max-message]',

			'{maxCount}'			: '[data-country-max-count]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, ev, name, value) {
				switch(name) {
					case 'select_type':
						self.inputGeneral().hide();

						if(value === 'textboxlist') {
							self.inputTextboxlist().show();
						}

						if(value === 'multilist') {
							self.inputMultilist().show();
						}

						if(value === 'checkbox') {
							self.inputCheckbox().show();
						}

						if(value === 'dropdown') {
							self.inputDropdown().show();
						}
						break;

					case 'multilist_size':
						self.inputMultilist().attr('size', value);
						break;

					case 'show_max_message':
						self.maxMessage().toggle(!!value);
						break;

					case 'max':
						self.maxCount().text(value);
						break;
				}
			}
		}
	});

	module.resolve();
});

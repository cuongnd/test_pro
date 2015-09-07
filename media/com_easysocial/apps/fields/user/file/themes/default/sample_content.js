<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.file', function($) {
	var module = this;

	EasySocial.Controller('Field.File', {
		defaultOptions: {
			'{sizeText}': '[data-field-file-size-text]',

			'{size}': '[data-field-file-size]',

			'{add}': '[data-field-file-add]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, ev, name, value) {
				switch(name) {
					case 'size_limit':
						self.size().text(value);
						break;

					case 'show_size_limit':
						self.sizeText().toggle(!!value);
						break;

					case 'file_limit':
						self.add().toggle((value < 1 || value > 1));
						break;
				}
			}
		}
	})

	module.resolve();
});

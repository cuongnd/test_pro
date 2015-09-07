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
			'{urlInput}': '[data-url-input]'
		}
	}, function(self) {
		return {
			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'placeholder':
						self.urlInput().attr('placeholder', value);
					break;

					case 'default':
						self.urlInput().val(value);
					break;
				}
			}
		}
	})

	module.resolve();
});

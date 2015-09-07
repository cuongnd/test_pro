<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.permalink', function($) {

	var module = this;

	EasySocial.Controller('Field.Permalink', {
		defaultOptions: {
			'{checkPermalink}'		: '[data-check-permalink]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'check_permalink':
						self.checkPermalink().toggle(!!value);
					break;
				}
			}
		}
	});

	module.resolve();
});

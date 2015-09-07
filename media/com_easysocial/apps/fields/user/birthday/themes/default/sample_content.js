<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.birthday', function($) {
	var module = this;

	EasySocial.Controller('Field.Birthday', {
		defaultOptions: {
			'{withCalendar}'	: '[data-with-calendar]',
			'{withoutCalendar}'	: '[data-without-calendar]',

			'{dateFormat}'	: '[data-without-calendar-format]'
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'calendar':
						self.withCalendar().toggle(value);
						self.withoutCalendar().toggle(!value);
					break;

					case 'date_format':
						self.dateFormat().hide();
						self.dateFormat().eq(parseInt(value) - 1).show();
					break;
				}
			}
		}
	});

	module.resolve();
});

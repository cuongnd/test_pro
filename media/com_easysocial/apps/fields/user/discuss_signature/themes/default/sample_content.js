<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.discuss_signature', function($) {
	var module = this;

	EasySocial.Controller('Field.Discuss_signature', {
		defaultOptions: {
			'{textarea}'	: '[data-textarea]'
		}
	}, function(self) {
		return {
			init: function() {
				if(EasyDiscuss) {
					EasyDiscuss.require().library('markitup').done(function(ED) {
						ED(self.textarea()).markItUp({set: 'bbcode_easydiscuss'});
					});
				}
			}
		}
	});

	module.resolve();
});

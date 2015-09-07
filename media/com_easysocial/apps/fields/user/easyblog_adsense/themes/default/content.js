<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.easyblog_desc', function($) {
	var module = this;

	EasySocial.Controller(
		'Field.EasyBlog_Desc',
		{
			defaultOptions:
			{
				required 		: false,

				"{field}"		: "[data-field-easyblog-desc]",

				"{input}"		: "[data-field-easyblog-desc-input]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
				},

				validateInput: function()
				{
					if( !self.options.required )
					{
						return true;
					}

					if( $._.isEmpty( self.input().val() ) )
					{
						return false;
					}

					return true;
				},

				"{input} change" : function( el , event )
				{
					if( !self.validateInput() )
					{
						self.element.addClass( 'error' );
					}
					else
					{
						self.element.removeClass( 'error' );
					}
				},

				"{self} onSubmit" : function( el , event , register )
				{
					if( !self.options.required )
					{
						register.push( true );
						return;
					}

					register.push( self.validateInput() );
					return;
				}
			}
		});

	module.resolve();
});

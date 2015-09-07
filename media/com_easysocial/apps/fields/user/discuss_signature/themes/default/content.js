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

	EasySocial.Controller(
		'Field.Discuss_signature',
		{
			defaultOptions:
			{
				required 		: false,

				'{item}'		: '[data-field-discussSignature-item]',

				'{notice}'		: '[data-check-notice]'
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					EasyDiscuss.require()
					.library(
						'markitup',
						'autogrow'
					)
					.done(function($) {
						$( '#<?php echo $inputName;?>' )
							.markItUp({set: 'bbcode_easydiscuss'})
							.autogrow({lineBleed: 1});
					});
				},

				validateInput : function()
				{
					var val 	= self.item().val();

					if( $._.isEmpty( val ) )
					{
						self.element.addClass('error');

						self.notice().html('<?php echo JText::_( 'PLG_FIELDS_TEXTAREA_VALIDATION_PLEASE_ENTER_SOME_VALUES' , true );?>');

						return false;
					}

					self.element.removeClass('error');

					return true;
				},

				'{self} onSubmit' : function(el, event, register)
				{
					// If field is not required, skip the checks.

					if(!self.options.required)
					{
						register.push(true);
						return;
					}

					register.push(self.validateInput());

					return;
				}
			}
		});

	module.resolve();
});

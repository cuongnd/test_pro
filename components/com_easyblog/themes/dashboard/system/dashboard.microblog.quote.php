<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
EasyBlog.require().library( 'autogrow' ).done(function( $ ){
	$( '#microblog-quote input[name=publish-post]' ).bind( 'click' , function(){

		var content 	= $( '#microblog-quote textarea[name=content]' ).val(),
			category	= $( '#microblog-quote select[name=category_id]' ).val(),
			privacy 	= $( '#microblog-quote select[name=private]' ).val(),
			quote		= $( '#microblog-quote textarea[name=quote]' ).val(),
			tags 		= getTags( 'quote' );

		EasyBlog.ajax( 'site.views.microblog.save',
			{
				'type'		: 'quote',
				'content'	: content,
				'quote'		: quote,
				'tags'		: tags,
				'category'	: category,
				'privacy'	: privacy
			},
			{
				beforeSend: function(){
					eblog.loader.loading( 'quickpost-loading' );
				},
				success: function( message ){

					$( '#eblog-message' )
						.removeClass( 'error info success' )
						.addClass( 'success' )
						.html( message )
						.show();


					// Once successful, reset the form so that the user can begin posting the next post.
					$( '#microblog-quote input[name=cancel]' ).click();
				},
				fail: function( message ){
					$( '#eblog-message' )
						.removeClass( 'error info success' )
						.addClass( 'error' )
						.html( message )
						.show();

					eblog.loader.doneLoading( "quickpost-loading" );
				}
			}
		);
	});

	$( '#microblog-quote input[name=cancel]' ).bind( 'click' , function(){

		// Reset the quote message
		$( '#microblog-quote textarea[name=quote]' ).val( '<?php echo $this->escape( JText::_( 'COM_EASYBLOG_MICROBLOG_QUOTE_EXAMPLE' , true ) );?>' );

	});

	// Add autogrow for quote area.
	var autogrowTimer;
	var autogrow = function(){
		clearTimeout(autogrowTimer);
		if ($.fn.autogrow)
		{
			$( '#microblog-quote textarea[name=quote]' ).autogrow({
				lineBleed: 1
			});
		}
		else
		{
			autogrowTimer = setTimeout(autogrow, 500);
		}
	}
	autogrowTimer = setTimeout(autogrow, 500);

	// Show / hide default quotes when necessary
	$( '#microblog-quote textarea[name=quote]' ).bind( 'focus blur' , function(){

		if( $(this).val() == '<?php echo $this->escape( JText::_( 'COM_EASYBLOG_MICROBLOG_QUOTE_EXAMPLE' , true ) );?>' )
		{
			$(this).val( '' );
		}
		else if( $(this).val() == '' )
		{
			$(this).val( '<?php echo $this->escape( JText::_( 'COM_EASYBLOG_MICROBLOG_QUOTE_EXAMPLE' , true ) );?>' );
		}
	});
});
</script>
<form id="microblog-quote" name="quick-post" method="post">
	<div class="quick-attachment clearfix"><?php echo JText::_( 'COM_EASYBLOG_MICROBLOG_QUOTE_ADD_A_QUOTE' );?></div>

	<div class="quick-output quote">
		<textarea name="quote" class="input quote textarea width-full"><?php echo JText::_('COM_EASYBLOG_MICROBLOG_QUOTE_EXAMPLE' );?></textarea>
	</div>

	<div class="mtm">
		<a href="javascript:void(0);" class="show-advanced-parameters buttons"><?php echo JText::_( 'COM_EASYBLOG_ADVANCED_PARAMETERS' );?></a>
	</div>

	<ul class="list-form tight reset-ul advanced-parameters">
		<li class="columns-2 add-seperator">
			<div>
				<div><?php echo $categories; ?></div>
			</div>
			<?php if( $this->acl->rules->enable_privacy ){ ?>
			<div>
				<div>
					<?php echo JHTML::_( 'select.genericlist' , EasyBlogHelper::getHelper( 'Privacy' )->getOptions() , 'private' , 'size="1" class="input select"' , 'value' , 'text' , $system->config->get( 'main_blogprivacy') );?>
				</div>
			</div>
			<?php } ?>
		</li>
		<li>
			<div class="input-label" style="background:#f5f5f5;border:1px solid #bbb;border-top:1px solid #888;margin:0!important;padding:6px">
				<?php echo JText::_('COM_EASYBLOG_DASHBOARD_QUICKPOST_QUOTE_SOURCE'); ?> <i class="small">(<?php echo JText::_('COM_EASYBLOG_DASHBOARD_QUICKPOST_OPTIONAL'); ?>)</i>
			</div>
			<div>
				<textarea class="input textarea width-full" rows="6" name="content" id="eblog-post-content"></textarea>
			</div>
		</li>
		<li class="advanced-parameters">
			<?php echo $this->fetch( 'dashboard.microblog.tags.php' , array( 'microblogType' => '#microblog-quote' ) ); ?>
		</li>
	</ul>

	<div class="ui-modfoot clearfix">
		<span id="quickpost-loading" class="float-r mts ir"></span>
		<input type="button" value="<?php echo JText::_('COM_EASYBLOG_PUBLISH_QUOTE_BUTTON'); ?>" class="buttons float-r" name="publish-post" />

		<?php echo $this->fetch( 'dashboard.microblog.autopost.php' ); ?>

		<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=dashboard&layout=entries' );?>" class="buttons sibling-l"><?php echo JText::_( 'COM_EASYBLOG_CANCEL_BUTTON' ); ?></a>
	</div>

</form>

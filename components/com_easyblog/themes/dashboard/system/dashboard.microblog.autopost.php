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
<?php
if(
	( $this->acl->rules->update_facebook && $system->config->get( 'integrations_facebook' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $system->my->id , 'FACEBOOK' ) ) ||
	( $this->acl->rules->update_twitter && $system->config->get( 'integrations_twitter' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $system->my->id , 'TWITTER' ) ) ||
	( $this->acl->rules->update_linkedin && $system->config->get( 'integrations_linkedin' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $system->my->id , 'LINKEDIN' ) )
){
?>
<div class="autopost-microblog ui-highlighter publish-to float-r mrs" style="position:relative;top:4px">
	<span><?php echo JText::_( 'COM_EASYBLOG_DASHBOARD_QUICKPOST_ALSO_PUBLISH_TO' );?>: </span>

	<?php if( $this->acl->rules->update_facebook && $system->config->get( 'integrations_facebook' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $system->my->id , 'FACEBOOK' ) ){?>
	<span class="ui-span">
		<input type="checkbox" name="socialshare[]" value="facebook" id="socialshare-facebook"<?php echo EasyBlogHelper::getHelper( 'SocialShare' )->hasAutoPost( $system->my->id , 'FACEBOOK' ) ? ' checked="checked"' : '';?> />
		<label class="socialshare-label" title="<?php echo JText::_( 'COM_EASYBLOG_SOCIALSHARE_FACEBOOK' ); ?>"><span class="ir ico-fb"><?php echo JText::_( 'COM_EASYBLOG_SOCIALSHARE_FACEBOOK' ); ?></span></label>
	</span>
	<?php } ?>

	<?php if( $this->acl->rules->update_twitter && $system->config->get( 'integrations_twitter' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $system->my->id , 'TWITTER' ) ){?>
	<span class="ui-span">
		<input type="checkbox" name="socialshare[]" value="twitter" id="socialshare-twitter"<?php echo EasyBlogHelper::getHelper( 'SocialShare' )->hasAutoPost( $system->my->id , 'TWITTER' ) ? ' checked="checked"' : '';?> />
		<label class="socialshare-label" title="<?php echo JText::_( 'COM_EASYBLOG_SOCIALSHARE_TWITTER' ); ?>"><span class="ir ico-tw"><?php echo JText::_( 'COM_EASYBLOG_SOCIALSHARE_TWITTER' ); ?></span></label>
	</span>
	<?php } ?>

	<?php if( $this->acl->rules->update_linkedin && $system->config->get( 'integrations_linkedin' ) && EasyBlogHelper::getHelper( 'SocialShare' )->isAssociated( $system->my->id , 'LINKEDIN' ) ){?>
	<span class="ui-span">
		<input type="checkbox" name="socialshare[]" value="linkedin" id="socialshare-linkedin"<?php echo EasyBlogHelper::getHelper( 'SocialShare' )->hasAutoPost( $system->my->id , 'LINKEDIN' ) ? ' checked="checked"' : '';?> />
		<label class="socialshare-label" title="<?php echo JText::_( 'COM_EASYBLOG_SOCIALSHARE_LINKEDIN' ); ?>"><span class="ir ico-ln"><?php echo JText::_( 'COM_EASYBLOG_SOCIALSHARE_LINKEDIN' ); ?></span></label>
	</span>
	<?php } ?>
</div>
<?php } ?>
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
<form name="facebook" action="index.php" method="post">
	<ol class="list-instruction reset-ul pa-15">
		<li>
			<?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_TWITTER_CREATE_APPLICATION_STEP'); ?> <a href="http://dev.twitter.com" target="_blank">http://dev.twitter.com</a>.
			<?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_TWITTER_CREATE_APP_HOW_TO' ); ?>
			<a href="http://stackideas.com/docs/easyblog/how-tos/setting-up-twitter-integration.html#create" target="_blank"><?php echo JText::_( 'COM_EASYBLOG_HERE' );?></a>
		</li>
		<li>
			<div><?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_TWITTER_COPY_CONSUMER_KEY_STEP'); ?></div>
			<div class="mini-form">
				<label for="integrations_twitter_api_key"><?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_TWITTER_CONSUMER_KEY' );?>:</label>
				<input type="text" name="integrations_twitter_api_key" id="integrations_twitter_api_key" value="<?php echo $this->config->get( 'integrations_twitter_api_key' );?>" class="input" style="width:300px" />
			</div>
		</li>
		<li>
			<div><?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_TWITTER_COPY_APP_SECRET_STEP'); ?></div>
			<div class="mini-form">
				<label for="integrations_twitter_secret_key"><?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_TWITTER_SECRET_KEY' );?>:</label>
				<input type="text" name="integrations_twitter_secret_key" id="integrations_twitter_secret_key" value="<?php echo $this->config->get( 'integrations_twitter_secret_key' );?>" class="input" style="width:300px" />
			</div>
		</li>
	</ol>
	<input type="hidden" name="integrations_twitter" value="1" />
	<input type="submit" class="button social twitter" value="<?php echo JText::_( 'COM_EASYBLOG_NEXT_STEP_BUTTON' );?>" />
	<input type="hidden" name="step" value="1" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="layout" value="twitter" />
	<input type="hidden" name="c" value="autoposting" />
	<input type="hidden" name="option" value="com_easyblog" />
	<?php echo JHTML::_( 'form.token' );?>
</form>

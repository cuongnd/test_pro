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

<fieldset class="eblog_login" style="border: solid 1px #cccccc; padding: 10px;">
	<h3><?php echo JText::_('COM_EASYBLOG_MEMBERS_LOGIN');?></h3>
	
	<p><?php echo JText::_('COM_EASYBLOG_PLEASE_LOGIN_TO_READ_FULL_ENTRY');?></p>
	
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="login">
		<div id="form-login-username" style="padding-bottom: 5px;">
			<label for="username"><?php echo JText::_('COM_EASYBLOG_USERNAME') ?></label><br />
			<input id="username" type="text" name="username" class="inputbox halfwidth" alt="username" size="18" />
		</div>
		<div id="form-login-password" style="padding-bottom: 5px;">
			<label for="passwd"><?php echo JText::_('PASSWORD') ?></label><br />
			<?php if( EasyBlogHelper::getJoomlaVersion() >= '1.6' ){ ?>
			<input id="passwd" type="password" name="password" class="inputbox halfwidth" size="18" alt="password" />
			<?php } else { ?>
			<input id="passwd" type="password" name="passwd" class="inputbox halfwidth" size="18" alt="password" />
			<?php } ?>
		</div>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) { ?>
			<div id="form-login-remember">
				<input id="remember" type="checkbox" name="remember" value="yes" alt="Remember Me"/>
				<label for="remember"><?php echo JText::_('COM_EASYBLOG_REMEMBER_ME') ?></label>
			</div>
	<?php } ?>
		<br />
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
		<ul>
		<?php if( EasyBlogHelper::getJoomlaVersion() >= '1.6' ){ ?>
			<li>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_users&view=reset' ); ?>">
				<?php echo JText::_('COM_EASYBLOG_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<li>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_users&view=remind' ); ?>">
				<?php echo JText::_('COM_EASYBLOG_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
			<?php
			$usersConfig = JComponentHelper::getParams( 'com_users' );
			if ($usersConfig->get('allowUserRegistration')) : ?>
			<li>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_users&view=registration' ); ?>">
					<?php echo JText::_('COM_EASYBLOG_CREATE_AN_ACCOUNT'); ?></a>
			</li>
			<?php endif; ?>
		<?php } else { ?>
			<li>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_user&view=reset' ); ?>">
				<?php echo JText::_('COM_EASYBLOG_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<li>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_user&view=remind' ); ?>">
				<?php echo JText::_('COM_EASYBLOG_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
			<?php
			$usersConfig = JComponentHelper::getParams( 'com_users' );
			if ($usersConfig->get('allowUserRegistration')) : ?>
			<li>
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_user&view=register' ); ?>">
					<?php echo JText::_('COM_EASYBLOG_CREATE_AN_ACCOUNT'); ?></a>
			</li>
			<?php endif; ?>		
		<?php } ?>
		</ul>
        <?php if( EasyBlogHelper::getJoomlaVersion() >= '1.6' ){ ?>
		<input type="hidden" value="com_users"  name="option">
		<input type="hidden" value="user.login" name="task">
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php } else { ?>
		<input type="hidden" value="com_user"  name="option">
		<input type="hidden" value="login" name="task">
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php } ?>
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</fieldset>
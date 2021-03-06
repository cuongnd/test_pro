<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="center mt-20 mb-20">
	<?php if( isset( $meta['avatar'] ) ) { ?>
	<div class="center">
		<img style="display:inline-block;" src="<?php echo $meta['avatar'];?>" alt="<?php echo $this->html( 'string.escape' , $meta['name'] );?>" class="es-avatar es-avatar-medium es-avatar-rounded" />
	</div>
	<?php } ?>
	<h2 class="h2"><?php echo JText::sprintf( 'COM_EASYSOCIAL_OAUTH_WELCOME_TITLE' , $meta[ 'name' ] );?></h2>
</div>
<hr />
<div class="row-fluid vertical-line vertical-line-50">
	<div class="span6">
		<h5 class="es-title"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_OAUTH_NEW_USERS' ); ?></h5>
		<hr />
		<p class="es-desp small"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_OAUTH_NEW_USERS_DESC' ); ?></p>
		<a class="btn btn-es-primary mt-10" href="<?php echo $createUrl;?>"><?php echo JText::_( 'COM_EASYSOCIAL_CREATE_ACCOUNT_BUTTON' ); ?> <i class="ies-arrow-right-2 ies-small"></i></a>
	</div>
	<div class="span6">
		<div class="pl-15">
			<h5 class="es-title"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_OAUTH_EXISTING_USERS' ); ?></h5>
			<hr />
			<p class="es-desp mb-10 small"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_OAUTH_EXISTING_USERS_DESC' );?></p>
			<form action="<?php echo JRoute::_( 'index.php' );?>" method="post">
				<input type="text" name="username" placeholder="Username" class="full-width">
				<input type="password" name="password" placeholder="Password" class="full-width">

				<div class="text-right">
					<button class="btn btn-es-primary"><?php echo JText::_( 'COM_EASYSOCIAL_LINK_ACCOUNT_BUTTON' ); ?> <i class="ies-arrow-right-2 ies-small"></i></button>
				</div>

				<?php echo $this->html( 'form.itemid' ); ?>
				<?php echo $this->html( 'form.token' ); ?>
				<input type="hidden" name="client" value="<?php echo $clientType;?>" />
				<input type="hidden" name="option" value="com_easysocial" />
				<input type="hidden" name="controller" value="registration" />
				<input type="hidden" name="task" value="oauthLinkAccount" />

			</form>
		</div>
	</div>
</div>

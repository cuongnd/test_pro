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
<div class="es-mod mod-es-logbox module-logbox<?php echo $suffix;?>">
	<div class="es-login-box vertical-line mt-20">
		<div class="row-fluid">
			<div class="span6">
				<div class="pl-20 pr-20">
					<form name="loginbox" id="loginbox" method="post" action="index.php">
						<legend class="mt-20"><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_TO_ACCOUNT_TITLE' );?></legend>
						<fieldset class="mt-20">

							<input type="text" class="full-width" name="username" placeholder="<?php echo $config->get( 'registraitions.emailasusername' ) ? JText::_( 'COM_EASYSOCIAL_LOGIN_EMAIL_PLACEHOLDER', true ) : JText::_( 'COM_EASYSOCIAL_LOGIN_USERNAME_PLACEHOLDER' , true );?>" />

							<input type="password" class="full-width" name="password" placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_PASSWORD_PLACEHOLDER' , true );?>" />

							<?php if( $params->get( 'show_remember_me' , true ) ){ ?>
							<label class="checkbox small mt-10">
								<input type="checkbox"> <span class="small"><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_REMEMBER_YOU' );?></span>
							</label>
							<?php } ?>

							<button type="submit" class="btn btn-es-success btn-block mt-20">
								<?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_TO_ACCOUNT_BUTTON' );?>
							</button>
						</fieldset>

						<?php if( $params->get( 'show_facebook_login' ) && $config->get( 'oauth.facebook.registration.enabled' ) && $config->get( 'registrations.enabled' )
								&& $config->get( 'oauth.facebook.secret' )
								&& $config->get( 'oauth.facebook.app' )
							){ ?>
						<div class="center es-signin-social">
							<p class="line">
								<strong><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_SIGNIN_SOCIAL' );?></strong>
							</p>

							<?php echo $facebook->getLoginButton( FRoute::registration( array( 'layout' => 'oauthDialog' , 'client' => 'facebook', 'external' => true ) , false ) ); ?>
						</div>
						<?php } ?>

						<hr />

						<div class="center">
							<?php if( $params->get( 'show_forget_username' , true ) ){ ?>
							<a class="text-error" href="<?php echo FRoute::profile( array( 'layout' => 'forgetUsername' ) );?>"> <?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_FORGOT_USERNAME' );?></a>
							<?php } ?>

							<?php if( $params->get( 'show_forget_password' , true ) ){ ?>
							 / <a class="text-error" href="<?php echo FRoute::profile( array( 'layout' => 'forgetPassword' ) );?>"> <?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_FORGOT_PASSWORD' );?></a>
							<?php } ?>
						</div>

						<input type="hidden" name="option" value="com_easysocial" />
						<input type="hidden" name="controller" value="profile" />
						<input type="hidden" name="task" value="login" />
						<input type="hidden" name="return" value="<?php echo $return; ?>" />
						<?php echo $modules->html( 'form.token' );?>
					</form>
				</div>
			</div>

			<?php if( $config->get( 'registrations.enabled' ) ){ ?>
			<div class="span6">
				<div class="pl-20 pr-20 modal-es-register">
					<h3><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_NO_ACCOUNT' );?></h3>
					<p class="center mb-20">
						<?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_REGISTER_NOW' );?>
					</p>
					<a class="btn btn-es-primary btn-large btn-block" href="<?php echo FRoute::registration();?>">
						<?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_REGISTER_NOW_BUTTON' );?>
					</a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

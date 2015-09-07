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
<div class="row-fluid">
	<div class="span12">
		<div class="es-unity">
			<?php echo $this->render( 'module' , 'es-unity-top' ); ?>

			<?php if( !$this->my->id ){ ?>
			<div class="es-login-box">
				<div class="row-fluid">
					<div class="span6 login-column">
						<div class="login-wrap">
							<form name="loginbox" id="loginbox" method="post" action="<?php echo JRoute::_( 'index.php' );?>">
								<legend class="mt-20"><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_TO_ACCOUNT_TITLE' );?></legend>
								<fieldset class="mt-20">

									<input type="text" class="full-width" name="username" placeholder="<?php echo $this->config->get( 'registraitions.emailasusername' ) ? JText::_( 'COM_EASYSOCIAL_LOGIN_EMAIL_PLACEHOLDER', true ) : JText::_( 'COM_EASYSOCIAL_LOGIN_USERNAME_PLACEHOLDER' , true );?>" />

									<input type="password" class="full-width" name="password" placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_PASSWORD_PLACEHOLDER' , true );?>" />

									<label class="checkbox small mt-10">
										<input type="checkbox"> <span class="small"><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_REMEMBER_YOU' );?></span>
									</label>

									<button type="submit" class="btn btn-es-success btn-block mt-20">
										<?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_TO_ACCOUNT_BUTTON' );?>
									</button>
								</fieldset>

								<?php if( $this->config->get( 'oauth.facebook.registration.enabled' ) && $this->config->get( 'registrations.enabled' ) ){ ?>
								<div class="center es-signin-social">
									<p class="line">
										<strong><?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_SIGNIN_SOCIAL' );?></strong>
									</p>

									<?php echo $facebook->getLoginButton( FRoute::registration( array( 'layout' => 'oauthDialog' , 'client' => 'facebook', 'external' => true ) , false ) ); ?>
								</div>
								<?php } ?>

								<hr />

								<div class="center">
									<a class="text-error" href="<?php echo FRoute::profile( array( 'layout' => 'forgetUsername' ) );?>"> <?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_FORGOT_USERNAME' );?></a> /
									<a class="text-error" href="<?php echo FRoute::profile( array( 'layout' => 'forgetPassword' ) );?>"> <?php echo JText::_( 'COM_EASYSOCIAL_LOGIN_FORGOT_PASSWORD' );?></a>
								</div>

								<input type="hidden" name="option" value="com_easysocial" />
								<input type="hidden" name="controller" value="profile" />
								<input type="hidden" name="task" value="login" />
								<input type="hidden" name="return" value="<?php echo $return; ?>" />
								<input type="hidden" name="returnFailed" value="<?php echo base64_encode( JRequest::getURI() ); ?>" />
								<?php echo $this->html( 'form.token' );?>
							</form>
						</div>
					</div>

					<?php if( $this->config->get( 'registrations.enabled' ) ){ ?>
					<div class="span6 register-column">
						<div class="register-wrap">
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
			<?php } ?>

			<div class="es-unity-happen">
				<div class="es-unity-title">
					<span><?php echo JText::_( 'COM_EASYSOCIAL_UNITY_HEADING_WHATS_GOING_ON' );?></span>
				</div>

				<div class="row-fluid mt-20">

					<div class="span4">
						<?php echo $this->render( 'module' , 'es-unity-sidebar-top' , 'site/unity/sidebar.module.wrapper' , array( 'style' => 'es-widget' ) ); ?>

						<?php echo $this->render( 'module' , 'es-unity-sidebar-bottom' , 'site/unity/sidebar.module.wrapper' , array( 'style' => 'es-widget' ) ); ?>
					</div>

					<div class="span8">

						<?php echo $this->render( 'module' , 'es-unity-content-top' , null , array( 'style' => 'es-widget' ) ); ?>

						<div class="es-content">

							<div data-unity-real-content>
								<?php echo $stream->html( false, $empty ); ?>

								<?php if( Foundry::user()->id == 0 ) { ?>
									<div class="pull-right">
										<a href="<?php echo $readmoreURL; ?>"><?php echo $readmoreText; ?></a>
									</div>
								<?php } ?>
							</div>
						</div>

						<?php echo $this->render( 'module' , 'es-unity-content-bottom' ); ?>

					</div>
				</div>
			</div>


		</div>
	</div>
</div>

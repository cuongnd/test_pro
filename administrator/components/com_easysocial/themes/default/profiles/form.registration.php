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
	<div class="span6">
		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION' );?></h3>

				<div class="es-controls-row">
					<div class="span5">
						<label for="registration_type"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_TYPE' );?></label>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_TYPE' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_TYPE_DESCRIPTION' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span7">
						<select name="params[registration]" class="registrationType input-xlarge">
							<option value="approvals"<?php echo $param->get( 'registration' ) == 'approvals' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_REQUIRE_APPROVALS' ); ?></option>
							<option value="verify"<?php echo $param->get( 'registration' ) == 'verify' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_REQUIRE_SELF_ACTIVATION' ); ?></option>
							<option value="auto"<?php echo $param->get( 'registration' ) == 'auto' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_REQUIRE_AUTO_LOGIN' ); ?></option>
							<option value="login"<?php echo $param->get( 'registration' ) == 'login' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_NORMAL' ); ?></option>
						</select>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="span6">
		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_EMAILS_TITLE' );?></h3>

				<div class="es-controls-row">
					<div class="span5">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_SEND_EMAILS_USER' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_SEND_EMAILS_USER' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_SEND_EMAILS_USER_DESC' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span7">
						<?php echo $this->html( 'grid.boolean' , 'params[email.users]' , $param->get( 'email.users' , true ) , '' , array() ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_SEND_EMAILS_ADMIN' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_SEND_EMAILS_ADMIN' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_REGISTRATION_SEND_EMAILS_ADMIN_DESC' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span7">
						<?php echo $this->html( 'grid.boolean' , 'params[email.moderators]' , $param->get( 'email.moderators' , true ) , '' , array() ); ?>
					</div>
				</div>

			</div>
		</div>
	</div>

</div>

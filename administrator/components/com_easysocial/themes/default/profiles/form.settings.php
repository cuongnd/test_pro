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
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_GENERAL' );?></h3>

				<div class="es-controls-row">
					<div class="span4">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_AVATAR' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_AVATAR' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_AVATAR_TIPS_DESC' ) , 'bottom' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_TITLE_PLACEHOLDER' ) ); ?>
						></i>
					</div>

					<div class="span8">

						<?php if( $profile->id ){ ?>
						<div class="mb-20">
							<img src="<?php echo $profile->getAvatar();?>" class="es-avatar es-avatar-medium es-shadowless es-borderthin es-bordergray" />
						</div>
						<?php } ?>

						<div>
							<input type="file" name="avatar" data-uniform />
						</div>
					</div>

				</div>

				<div class="es-controls-row">
					<div class="span4">
						<label for="title"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_TITLE' );?></label>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_TITLE' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_TITLE_TIPS_DESC' ) , 'bottom' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_TITLE_PLACEHOLDER' ) ); ?>
						></i>
					</div>
					<div class="span8">
						<input type="text" name="title" id="title" class="input-xlarge" value="<?php echo $profile->title;?>"/>
					</div>
				</div>


				<div class="es-controls-row">
					<div class="span4">
						<label for="title"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ALIAS_TITLE' );?></label>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ALIAS_TITLE' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ALIAS_TIPS_DESC' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<input type="text" name="alias" id="alias" class="input-xlarge" value="<?php echo $profile->alias;?>"/>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span4">
						<label for="description"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DESCRIPTION' );?></label>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DESCRIPTION' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DESCRIPTION_TIPS_DESC' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<textarea name="description"
							id="description"
							class="full-width small"
							data-profile-description
						><?php echo $profile->description;?></textarea>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span4">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PUBLISHING_STATUS' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PUBLISHING_STATUS' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PUBLISHING_STATUS_DESCRIPTION' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<?php echo $this->html( 'grid.boolean' , 'state' , $profile->state , 'state' ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span4">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DEFAULT_PROFILE' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DEFAULT_PROFILE' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DEFAULT_PROFILE_DESCRIPTION' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<?php echo $this->html( 'grid.boolean' , 'default' , $profile->default , 'default' ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span4">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PROFILE_DELETION' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PROFILE_DELETION' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PROFILE_DELETION_DESCRIPTION' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<?php echo $this->html( 'grid.boolean' , 'params[delete_account]' , $param->get( 'delete_account') , 'params[delete_account]' ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span4">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PROFILE_REGISTRATION' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PROFILE_REGISTRATION' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_PROFILE_REGISTRATION_DESCRIPTION' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<?php echo $this->html( 'grid.boolean' , 'registration' , $profile->registration , 'registration' ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span4">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ORDERING' );?>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ORDERING' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_ORDERING_DESCRIPTION' ) , 'bottom' ); ?>
						></i>
					</div>
					<div class="span8">
						<input type="text" class="input-mini center" value="<?php echo $profile->ordering; ?>" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="span6">
		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_LAYOUT' );?></h3>

				<div class="es-controls-row">
					<div class="span4">
						<label for="theme"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DEFAULT_THEME' );?></label>
						<i class="icon-es-help pull-right"
							<?php echo $this->html( 'bootstrap.popover' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DEFAULT_THEME' ) , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DEFAULT_THEME_DESCRIPTION' ) , 'bottom' , JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_DESCRIPTION_PLACEHOLDER' ) ); ?>
						></i>
					</div>
					<div class="span8">
						<select name="params[theme]" id="theme">
							<option value=""<?php echo !$param->get( 'theme' ) ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_SELECT_A_THEME' ); ?></option>
							<?php foreach( $themes as $theme ){ ?>
							<option value="<?php echo $theme->element;?>"<?php echo strtolower( $theme->element ) == strtolower( $param->get( 'theme' ) ) ? ' selected="selected"' : '';?>><?php echo JText::_( $theme->name ); ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_GROUPS' );?></h3>
				<p class="small"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_GROUPS_DESC' );?></p>

				<div class="es-controls-row">
					<div class="span4">
						<label for="theme"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_GROUPS_DEFAULT_USER_GROUP' );?></label>
					</div>
					<div class="span8">
						<?php echo $this->html( 'tree.groups' , 'gid' , $profile->gid , $guestGroup ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
<ul id="userForm" class="nav nav-tabs nav-tabs-icons">
	<li class="tabItem active">
		<a data-foundry-toggle="tab" href="#settings">
			<i class="icon-jar jar-switch_settings" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_PROFILE_GENERAL' );?></span>
		</a>
	</li>

	<li class="tabItem">
		<a data-foundry-toggle="tab" href="#registrations">
			<i class="icon-jar jar-email_envelope_stack" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_PROFILE_REGISTRATION' );?></span>
		</a>
	</li>

	<?php if( $isNew ){ ?>
	<li class="tabItem inactive">
		<a href="javascript:void(0);" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_DISABLED_INFO' );?>" data-es-provide="tooltip">
			<i class="icon-jar jar-user_woodenframe" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_DEFAULT_AVATARS' );?></span>
		</a>
	</li>

	<li class="tabItem inactive">
		<a href="javascript:void(0);" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_DISABLED_INFO' );?>" data-es-provide="tooltip">
			<i class="icon-jar jar-id_card" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_CUSTOM_FIELDS' );?></span>
		</a>
	</li>

	<li class="tabItem inactive">
		<a href="javascript:void(0);" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_DISABLED_INFO' );?>" data-es-provide="tooltip">
			<i class="icon-jar jar-public" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_PRIVACY' );?></span>
		</a>
	</li>

	<li class="tabItem inactive">
		<a href="javascript:void(0);" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_DISABLED_INFO' );?>" data-es-provide="tooltip">
			<i class="icon-jar jar-tool_box_open"></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_ACCESS' );?></span>
		</a>
	</li>
<?php } else { ?>
	<li class="tabItem">
		<a data-foundry-toggle="tab" href="#avatars">
			<i class="icon-jar jar-user_woodenframe" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_DEFAULT_AVATARS' );?></span>
		</a>
	</li>

	<li class="tabItem">
		<a data-foundry-toggle="tab" href="#fields" class="fields">
			<i class="icon-jar jar-web_standard_left_sidebar" ></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_CUSTOM_FIELDS' );?></span>
		</a>
	</li>

	<li class="tabItem">
		<a data-foundry-toggle="tab" href="#privacy">
			<i class="icon-jar jar-clipboard_tick"></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_PRIVACY' );?></span>
		</a>
	</li>

	<li class="tabItem">
		<a data-foundry-toggle="tab" href="#access">
			<i class="icon-jar jar-tool_box_open"></i>
			<span class="help-block"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_TAB_ACCESS' );?></span>
		</a>
	</li>
	<?php } ?>
</ul>

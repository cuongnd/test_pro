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
<form name="adminForm" id="adminForm" class="profileForm" method="post" enctype="multipart/form-data" data-user-form>

<div class="row-fluid filter-bar">
	<div class="es-controls-row">
		<div class="span3">
			<label for="profileType">
				<?php echo JText::_( 'COM_EASYSOCIAL_USER_SELECT_PROFILE_TYPE_FOR_NEW_USER' ); ?>
			</label>
			<i data-placement="bottom" data-title="<?php echo JText::_( 'COM_EASYSOCIAL_USER_SELECT_PROFILE_TYPE' );?>" data-content="<?php echo JText::_( 'COM_EASYSOCIAL_USER_SELECT_PROFILE_TYPE_DESC' );?>" data-es-provide="popover" class="icon-es-help pull-right" data-original-title=""></i>
		</div>
		<div class="span9">
			<span class="input-append">
				<input type="text" class="inputbox input- required" size="40" disabled="disabled" readonly="readonly" aria-required="true" required="required"
					value="<?php echo $profile->get( 'title' );?>"
					data-profile-title>
				<a class="btn btn-es-primary" data-user-select-profile>
					<i class=" ies-list ies-small icon-white mr-5"></i>
					<?php echo JText::_( 'COM_EASYSOCIAL_SELECT_A_PROFILE' ); ?>
				</a>
			</span>
		</div>
	</div>
	<div class="es-controls-row" style="padding-top:0px;">
		<div class="span3">&nbsp;</div>
		<div class="span9">
			<input type="checkbox" id="autoapproval" name="autoapproval" value="1" /> Also automatically approve this user.
		</div>
	</div>
</div>

<div data-user-new-content>
	<?php echo $this->includeTemplate( 'admin/users/form.new.content' ); ?>
</div>

<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="users" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="profileId" value="<?php echo $profile->id;?>" />
<?php echo JHTML::_( 'form.token' );?>

</form>

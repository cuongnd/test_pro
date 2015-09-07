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
<form name="adminForm" id="adminForm" class="profileForm" method="post" enctype="multipart/form-data">
	<div class="wrapper accordion">
		<div class="tab-box tab-box-alt">
			<div class="tabbable">

				<?php echo $this->loadTemplate( 'admin/profiles/form.tabs' , array( 'isNew' => $profile->id == 0 ) ); ?>

				<div class="tab-content">
					<div id="settings" class="tab-pane active in">
						<?php echo $this->includeTemplate( 'admin/profiles/form.settings' ); ?>
					</div>

					<div id="registrations" class="tab-pane">
						<?php echo $this->includeTemplate( 'admin/profiles/form.registration' ); ?>
					</div>

					<?php if( $profile->id ){ ?>
					<div id="avatars" class="tab-pane">
						<?php echo $this->includeTemplate( 'admin/profiles/form.avatars' ); ?>
					</div>

					<div id="fields" class="tab-pane">
						<?php echo $this->includeTemplate( 'admin/profiles/form.fields' ); ?>
					</div>

					<div id="privacy" class="tab-pane">
						<?php echo $this->includeTemplate( 'admin/profiles/form.privacy' ); ?>
					</div>

					<div id="access" class="tab-pane">
						<?php echo $this->includeTemplate( 'admin/profiles/form.access' ); ?>
					</div>
					<?php } ?>

				</div>

			</div>
		</div>
	</div>

	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="controller" value="profiles" />
	<input type="hidden" name="task" value="store" />
	<input type="hidden" name="id" value="<?php echo $profile->id; ?>" />
	<?php echo JHTML::_( 'form.token' );?>
</form>

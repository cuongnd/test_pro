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
<?php if( $this->template->get( 'registration_progress' ) ){ ?>
<?php echo $this->includeTemplate( 'site/registration/default.progress' , array( 'currentStep' => $currentStep , 'totalSteps' => $profile->getTotalSteps() , 'steps' => $steps , 'registration' => $registration ) ); ?>
<?php } ?>

<?php if( $totalProfiles > 1 && $this->template->get( 'registration_profile_selected' ) ){ ?>
<div class="profile-selected">
	<i class="icon-es-aircon-user mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_REGISTERING_UNDER_PROFILE' ); ?> <strong><?php echo $profile->get( 'title' ); ?></strong>.
	<a href="<?php echo FRoute::registration();?>"><?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATION_SWITCH_PROFILE' );?></a>
</div>
<?php } ?>

<form class="form-horizontal has-privacy" enctype="multipart/form-data" method="post" action="<?php echo JRoute::_( 'index.php' );?>" id="registrationForm" data-registration-form>

	<!-- Custom fields -->
	<?php if( $fields ) { ?>
		<?php foreach( $fields as $field ){ ?>
			<?php echo $this->loadTemplate( 'site/registration/steps.field' , array( 'field' => $field , 'errors' => $errors ) ); ?>
		<?php } ?>
	<?php } ?>

	<div class="controls small mt-20">
		<?php echo JText::_( 'COM_EASYSOCIAL_REGISTRATIONS_REQUIRED' );?>
	</div>

	<!-- Actions -->
	<div class="form-actions">
		<?php if( $currentStep != 1 ){ ?>
		<button class="btn btn-es btn-medium pull-left" data-registration-previous><?php echo JText::_( 'COM_EASYSOCIAL_PREVIOUS_BUTTON' ); ?></button>
		<?php } ?>
		<button class="btn btn-es-primary btn-medium pull-right" data-registration-submit><?php echo JText::_( 'COM_EASYSOCIAL_SUBMIT_BUTTON' );?></button>
	</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="currentStep" value="<?php echo $currentIndex; ?>" />
	<input type="hidden" name="controller" value="registration" />
	<input type="hidden" name="task" value="saveStep" />
	<input type="hidden" name="option" value="com_easysocial" />
</form>

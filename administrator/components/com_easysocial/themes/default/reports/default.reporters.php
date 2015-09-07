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
<ul class="unstyled es-reports" data-reporters>
	<?php foreach( $reporters as $report ){ ?>
	<li class="es-report" data-reporters-item data-id="<?php echo $report->id;?>">

		<div class="es-report-msg">
			<?php echo $report->get( 'message' ); ?>
		</div>

		<div class="pull-left es-report-reporter">
			<a href="<?php echo $report->getUser()->getPermalink();?>" class="es-avatar es-avatar-mini pull-left" target="_blank">
				<img src="<?php echo $report->getUser()->getAvatar();?>" alt="<?php echo $this->html( 'string.escape' , $report->getUser()->getName() ); ?>" />
			</a>
			<span class="es-report-username ml-10">
				<a href="<?php echo $report->getUser()->getPermalink();?>" target="_blank"><?php echo $report->getUser()->getName();?></a>
			</span>

			<span class="es-report-ip">
				<?php echo $report->ip;?>
			</span>

		</div>

		<div class=" pull-right es-report-action">
			<a class="btn btn-es-danger btn-mini btn-remove" data-remove-item>
				<i class="ies-cancel-2"></i>
				<?php echo JText::_( 'COM_EASYSOCIAL_REMOVE_BUTTON' ); ?>
			</a>
		</div>
	</li>
	<?php } ?>
</ul>

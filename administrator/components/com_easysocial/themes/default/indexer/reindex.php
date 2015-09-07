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
<form action="index.php" method="post" name="adminForm" class="esForm" id="adminForm">
<div data-indexer-container class="row-fluid">
	<div class="span6">
		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_GENERAL' );?></h3>

				<div><?php echo JText::_( 'COM_EASYSOCIAL_INDEXER_REINDEX_WARNING_INFO' ); ?></div>
				<div data-indexer-message style="display:none;"><?php echo JText::_( 'COM_EASYSOCIAL_INDEXER_REINDEX_PROCESSING' ); ?> </div>
				<div>
					<a href="javascript:void('0');" class="mt-20 btn btn-es-primary btn-large" data-start-button><?php echo JText::_( 'COM_EASYSOCIAL_INDEXER_REINDEX_CLICK_TO_START' ); ?></a>
				</div>

			</div>
		</div>
	</div>

	<div class="span6">
		<div class="span12 widget-box">
			<h3><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_GENERAL' );?></h3>

			<div id="appsTable">
				<div class="es-progress-wrap">
					<div class="progress progress-success progress-striped">
						<div class="bar" style="width: 0%" data-indexer-bar > </div>
						<div class="progress-result" data-indexer-result >0%</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="view" value="indexer" />
<input type="hidden" name="controller" value="indexer" />
<input type="hidden" name="task" value="" />

</form>

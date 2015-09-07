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
<h3><?php echo JText::_( 'COM_EASYSOCIAL_USERS_POINTS_ACHIEVEMENT_HISTORY' ); ?></h3>
<hr />
<p>
	<?php echo JText::_( 'COM_EASYSOCIAL_USERS_POINTS_ACHIEVEMENT_HISTORY_DESC' ); ?>
</p>
<table class="table table-striped table-es">
	<thead>
		<td class="center" width="5%">
			<?php echo JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ); ?>
		</td>
		<td>
			<?php echo JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ACHIEVEMENT' ); ?>
		</td>
		<td>
			<?php echo JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_REASON' ); ?>
		</td>
	</thead>
	<tbody>
		<?php if( $pointsHistory ){ ?>
			<?php foreach( $pointsHistory as $history ){ ?>
			<tr>
				<td class="center">
					<?php echo $history->id;?>
				</td>
				<td>
					<div>
					<?php if( $history->points > 0 ){ ?>
						<?php echo ucfirst( JText::_( 'COM_EASYSOCIAL_POINTS_EARNED' ) );?>
					<?php } else { ?>
						<?php echo ucfirst( JText::_( 'COM_EASYSOCIAL_POINTS_LOST' ) );?>
					<?php } ?>

					<?php echo $history->points; ?> <?php echo JText::_( 'COM_EASYSOCIAL_POINTS_POINTS' );?>
					</div>

					<div class="points-date small">
						<em><?php echo $this->html( 'string.date' , $history->created );?></em>
					</div>
				</td>
				<td>
					<?php echo $history->points_title; ?>
				</td>
			</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td class="empty" colspan="3">
					<?php echo JText::_( 'COM_EASYSOCIAL_USERS_DID_NOT_EARN_POINTS_YET' ); ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

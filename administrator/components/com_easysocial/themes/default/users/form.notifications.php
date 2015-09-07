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
<?php foreach( array( 'system', 'others' ) as $group ) {
	if( isset( $alerts[ $group ] ) ) {
?>
<?php foreach( $alerts[$group] as $alert ) { ?>
	<?php if( isset( $alert[ 'title' ] ) ){ ?>
	<h3><?php echo JText::_( $alert['title'] ); ?></h3>
	<hr />
	<?php } ?>

	<table class="table table-striped table-noborder">
		<thead>
			<tr>
				<td width="30%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="15%" class="center"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_NOTIFICATION_SYSTEM'); ?></td>
				<td width="15%" class="center"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_NOTIFICATION_EMAIL'); ?></td>
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $alert[ 'data' ] as $rule ){ ?>
			<tr>
				<td><?php echo $rule->getTitle(); ?></td>
				<td>
					<i class="icon-es-help" <?php echo $this->html( 'bootstrap.popover' , $rule->getTitle() , $rule->getDescription() , 'bottom' ); ?>></i>
				</td>
				<td class="pa-5 center"><?php echo $rule->system >= 0 ? $this->html( 'grid.boolean', 'notifications[system][' . $rule->id . ']', $rule->system ) : JText::_( 'COM_EASYSOCIAL_PROFILE_NOTIFICATION_NOT_APPLICABLE' ); ?></td>
				<td class="pa-5 center"><?php echo $rule->email >= 0 ? $this->html( 'grid.boolean', 'notifications[email][' . $rule->id .']', $rule->email ) : JText::_( 'COM_EASYSOCIAL_PROFILE_NOTIFICATION_NOT_APPLICABLE' ); ?></td>
				<td>&nbsp;</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
<?php }
	} //if isset()
}

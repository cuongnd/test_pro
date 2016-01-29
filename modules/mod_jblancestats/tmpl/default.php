<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	25 June 2012
 * @file name	:	modules/mod_jblancestats/tmpl/default.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$sh_users 	= $params->get('total_users', 1);
$sh_active 	= $params->get('active_projects', 1);
$sh_total 	= $params->get('total_projects', 1);
?>
<table width="100%">
	<?php if($sh_users) : ?>
	<tr>
		<td>
			<?php echo JText::_('MOD_JBLANCE_LABEL_TOTAL_USERS'); ?>:
		</td>
		<td align="center">
			<strong><?php echo $total_users; ?></strong>
		</td>
	</tr>
	<?php endif; ?>
	
	<?php if($sh_active) : ?>
	<tr>
		<td>
			<?php echo JText::_('MOD_JBLANCE_LABEL_TOTAL_OPEN_PROJECTS'); ?>:
		</td>
		<td align="center">
			<strong><?php echo $active_projects; ?></strong>
		</td>
	</tr>
	<?php endif; ?>
	
	<?php if($sh_total) : ?>
	<tr>
		<td>
			<?php echo JText::_('MOD_JBLANCE_LABEL_TOTAL_PROJECTS'); ?>:
		</td>
		<td align="center">
			<strong><?php echo $total_projects; ?></strong>
		</td>
	</tr>
	<?php endif; ?>
</table>

<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$supperAdmin=JFactory::isSupperAdmin();
defined('_JEXEC') or die;
?>
<ul class="nav nav-list">
	<?php if ($this->userIsSuperAdmin): ?>
		<li class="nav-header"><?php echo JText::_('COM_CONFIG_SYSTEM'); ?></li>
		<li><a href="index.php?option=com_config"><?php echo JText::_('COM_CONFIG_GLOBAL_CONFIGURATION'); ?></a></li>
		<li class="divider"></li>
	<?php endif; ?>
	<li class="nav-header"><?php echo JText::_('COM_CONFIG_COMPONENT_FIELDSET_LABEL'); ?></li>
	<?php foreach ($this->components as $component) : ?>
		<?php
		$active = '';
		if ($this->currentComponent == $component->element)
		{
			$active = ' class="active"';
		}
		?>
		<li <?php echo $active; ?>>
			<a href="index.php?option=com_config&view=component&component=<?php echo $component->element; ?>"><?php echo JText::_($component->element); ?><?php if(!$supperAdmin){ ?> <br/>[<?php echo $component->website ?>] <?php } ?></a>
		</li>
	<?php endforeach; ?>
</ul>

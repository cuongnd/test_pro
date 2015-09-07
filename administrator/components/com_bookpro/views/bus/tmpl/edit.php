<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.combobox');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_modules&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">


	<div class="form-horizontal">
        <fieldset>
            <div class = "control-group">
                <div class = "control-label"><?php echo $this->form->getLabel('title'); ?></div>
                <div class = "controls"><?php echo $this->form->getInput('title'); ?></div>
            </div>
            <div class = "control-group">
                <div class = "control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                <div class = "controls"><?php echo $this->form->getInput('alias'); ?></div>
            </div>
            <div class = "control-group">
                <div class = "control-label"><?php echo $this->form->getLabel('image'); ?></div>
                <div class = "controls"><?php echo $this->form->getInput('image'); ?></div>
            </div>
            <div class = "control-group">
                <div class = "control-label"><?php echo $this->form->getLabel('value'); ?></div>
                <div class = "controls"><?php echo $this->form->getInput('value'); ?></div>
            </div>
            <div class = "control-group">
                <div class = "control-label"><?php echo $this->form->getLabel('code'); ?></div>
                <div class = "controls"><?php echo $this->form->getInput('code'); ?></div>
            </div>
            <div class = "control-group">
                <div class = "control-label"><?php echo $this->form->getLabel('desc'); ?></div>
                <div class = "controls"><?php echo $this->form->getInput('desc'); ?></div>
            </div>
        </fieldset>
		<input type="hidden" name="option" value="com_bookpro" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

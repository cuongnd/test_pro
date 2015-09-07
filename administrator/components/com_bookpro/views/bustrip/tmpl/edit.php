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

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('from'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('from'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('to'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('to'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('roundtrip'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('roundtrip'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('bus_id'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('bus_id'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('tax'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('tax'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('start_time'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('start_time'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('km'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('km'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('duration'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('duration'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('duration2'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('duration2'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('summary'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('summary'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('trip_information'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('trip_information'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
        </div>

		<input type="hidden" name="option" value="com_bookpro" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

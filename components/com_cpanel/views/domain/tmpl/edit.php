<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$filed_set=$this->form->getFieldset();
?>
<script type="text/javascript">

</script>
<?php echo $this->render_toolbar() ?>
<form action="<?php echo JRoute::_('index.php?option=com_cpanel&view=domain&layout=edit&id=' . (int) $this->item->id); ?>" method="post"  name="adminForm" id="adminForm" class="form-validate">

	<div class="form-horizontal">
        <?php foreach ($this->form->getFieldset() as $field) : ?>
            <div class="form-group">
                <label for="<?php $field->name ?>" class="col-sm-2 control-label"><?php echo $field->label; ?></label>
                <div class="col-sm-10">
                    <?php echo $field->input; ?>
                </div>
            </div>
        <?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>

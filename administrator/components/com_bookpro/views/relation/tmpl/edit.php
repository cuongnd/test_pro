<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/9/2015
 * Time: 2:57 PM
 */
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $this->form->getLabel('title'); ?></label>
                <div class="col-sm-10">
                    <?php echo $this->form->getInput('title'); ?>
                </div>
            </div>
        </div>

    </div>

    <div>
        <input type="hidden" name="task" value="" /> 
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
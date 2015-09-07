<?php
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$input=JFactory::getApplication()->input;
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>

                  <div class="control-group">
                    <div class="control-label">Type</div>
                    <div class="controls"><?php echo $this->item->type ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('obj_id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('obj_id'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('path'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('path'); ?></div>
                </div>	

               
            </fieldset>	
        </div>
        <?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>

    </div>



    <div>

        <?php
        $session = JFactory::getSession();
        $sessionType = $session->get('type');

        echo $this->form->getInput('type','sdsdssd',$input->get('type','','string'));
        ?>
        <input type="hidden" name="task" value="" /> 
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
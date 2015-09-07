<?php
defined('_JEXEC') or die;
AImporter::helper('currency');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="form-horizontal">
            <fieldset>
            
           		 <div class="control-group">
                    <div class="control-label"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?></div>
                    <div class="controls"><?php echo $this->order->order_number; ?></div>
                </div>
                 <div class="control-group">
                    <div class="control-label"><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL') ?></div>
                    <div class="controls"><?php echo CurrencyHelper::formatprice($this->order->total); ?></div>
                </div>
				
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('amount'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('amount'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('gateway'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('gateway'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('tx_id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('tx_id'); ?></div>
                </div>
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>
            </fieldset>	
        </div>
    </div>
    <div>

         <?php echo $this->form->getInput('order_id',null,$this->order_id); ?> 
        <input type="hidden" name="task" value="" /> 
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
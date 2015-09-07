<?php
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<div class = "row-fluid">
        <?php
        $this->form->setValue('cid_to', null, $this->cid_to);
        $this->form->setValue('ordernumber', null, reset($subjects)->ordernumber);
        ?>
    <?php
    if ($this->parent_id) {
        $model = new BookProModelMessages();
        $subjects = $model->buildParentQuery();
        $subjects=array_reverse($subjects);
        $k = 0;
        for ($i = 0; $i < count($subjects); $i++) {
            $items = $subjects[$i];
            $date = new DateTime($items->created);
            $date = $date->format('d-M-Y H:i');
            ?>
            <div class = "messages">
                <div class = "header row-fluid">
                    <div class = "span6">
                        <?php echo $items->fusername ?> <b>|</b> <?php echo $items->tusername ?>
                    </div>
                    <div class = "span6" style = "text-align: right">
                        <?php echo $date ?>
                    </div>
                </div>
                <div class = "body row-fluid">
                    <div class = "panel-group" id = "accordion<?php echo $i ?>">
                        <div class = "panel panel-default">
                            <div class = "panel-heading">
                                <h4 class = "panel-title">
                                    <a data-toggle = "collapse" data-parent = "#accordion<?php echo $i ?>" href = "#collapseOne<?php echo $i ?>">
                                        <strong><?php echo JText::_('COM_BOOKPRO_MESSAGE_SUBJECT') ?></strong>:<?php echo $items->subject ?>
                                    </a>
                                </h4>
                            </div>
                            <div id = "collapseOne<?php echo $i ?>" class = "panel-collapse collapse">
                                <div class = "panel-body">
                                    <div class = "content-message row-fluid"><strong><?php echo JText::_('COM_BOOKPRO_MESSAGE') ?></strong>:<br/><?php echo $items->message ?></div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class = "footer row-fluid">

                </div>
            </div>
        <?php

        }
    }


    ?>


    <form action = "<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int)$this->item->id); ?>" method = "post" id = "adminForm" name = "adminForm" class = "form-validate">
            <div class = "messages">
                <div class="header row-fluid">
                    <div class="span6">
                        <?php echo $items->fusername ?> <b>|</b> <?php echo $items->tusername ?>
                    </div>
                    <div class="span6" style="text-align: right">
                        <?php echo $date ?>
                    </div>
                </div>
                <div class="body row-fluid">
                    <div class="row-fluid"><strong><?php echo $this->form->getLabel('cid_to'); ?></strong><br/><?php echo $this->form->getInput('cid_to'); ?></div>
                    <div class="subject row-fluid"><strong><?php echo $this->form->getLabel('subject'); ?></strong><br/><?php echo $this->form->getInput('subject'); ?></div>
                    <div class="ordernumber row-fluid"><strong><?php echo $this->form->getLabel('ordernumber'); ?></strong><br/><?php echo AHtml::getFilterSelect('ordernumber', JText::_('Order Number'), $this->orders, reset($subjects)->ordernumber, false, '', 'id', 'order_number'); ?></div>
                    <div class="content-message row-fluid"><strong><?php echo $this->form->getLabel('message'); ?></strong><br/><?php echo $this->form->getInput('message'); ?></div>
                    <div class="content-message row-fluid"><strong><?php echo $this->form->getLabel('state'); ?></strong><br/><?php echo $this->form->getInput('state'); ?></div>
                </div>
                <div class="footer row-fluid" style="text-align: right">
                </div>

            </div>
            <div>
                <?php echo $this->form->getInput('parent_id', null, $this->parent_id); ?>
                <input type = "hidden" name = "task" value = ""/>
                <input type = "hidden" name = "return" value = "<?php echo JRequest::getCmd('return'); ?>"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>

        </form>




</div>

<style type="text/css">
    .messages{
        border: 1px solid #cdcdcd;
        margin: 20px 50px 20px 50px;
        padding: 1px 0 10px;
        border-radius: 4px;
    }
    .messages .header
    {
        background-color: #f5f5f5;
        background-image: none;
        border-bottom: 1px solid #e5e5e5;
        padding: inherit;

    }
    .messages #jform_message
    {
        width: 80%;
    }
    .messages .header > div
    {
        padding: 5px;
    }
    .messages .footer
    {

    }
    .messages .body
    {
        padding: 5px;
    }
    .content-message{
    }
    .send-message{
        margin-left: 50px;
    }
    .messages div.panel
    {
        border: none;
        border-radius:0;
    }
</style>



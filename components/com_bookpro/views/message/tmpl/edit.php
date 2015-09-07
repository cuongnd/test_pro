<?php
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
?>
<div class = "span12">
    <div class = "span2">
        <?php echo $this->loadTemplate('menu') ?>
    </div>
    <div class = "span9">
        <form action = "<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int)$this->item->id); ?>" method = "post" id = "adminForm" name = "adminForm" class = "form-validate">
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
                    <div class = "subject row-fluid"><strong><?php echo JText::_('COM_BOOKPRO_MESSAGE_SUBJECT') ?></strong>:<br/><input type = "text" name = "subject" class = "required"/></div>
                    <div class = "ordernumber row-fluid"><strong><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?></strong>:<br/><?php ?><?php echo AHtmlFrontEnd::getFilterSelect('ordernumber', JText::_('Order Number'), $this->orders, reset($subjects)->ordernumber, false, '', 'id', 'order_number'); ?></div>
                    <div class = "content-message row-fluid"><strong><?php echo JText::_('COM_BOOKPRO_MESSAGE') ?></strong>:<br/>
                        <?php $editor = JFactory::getEditor();
                        ?>
                        <?php echo $editor->display('message', '', '100%;', '550', '75', '20', array('pagebreak', 'readmore', 'modulesanywhere', 'article')); ?>


                    </div>
                </div>
                <div class = "footer row-fluid" style = "text-align: right">
                    <input style = "margin: 0px 10px" type = "submit" class = "btn btn-primary" name = "submit" id = "submit" value = "<?php echo JText::_('COM_BOOKPRO_MESSAGE_SEND'); ?>"/>
                </div>

            </div>

            <div>
                <input type = "hidden" name = "parent_id" value = "<?php echo JRequest::getVar('parent_id') ?>"/>
                <input type = "hidden" name = "option" value = "<?php echo OPTION; ?>"/>
                <input type = "hidden" name = "controller" value = "message"/>
                <input type = "hidden" name = "cid_from" value = "<?php echo $user->id; ?>"/>
                <input type = "hidden" name = "task" value = "save"/>
                <input type = "hidden" name = "state" value = "1"/>
                <input type = "hidden" name = "boxchecked" value = "1"/>
                <input type = "hidden" name = "cid[]" value = "<?php echo $this->obj->id; ?>" id = "cid"/>
                <input type = "hidden" name = "Itemid" value = "<?php echo JRequest::getVar(Itemid); ?>" id = "Itemid"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </form>

        <?php
        if ($this->parent_id) {
            $model = new BookProModelMessages();
            $subjects = $model->buildParentQuery();

            $k = 0;
            for ($i = 0; $i < count($subjects); $i++) {
                $items = $subjects[$i];
                $date = JFactory::getDate($items->created)->format('d-M-Y H:i');
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
                $k++;
            }
        }
        ?>
        <style type = "text/css">
            .messages {
                border: 1px solid #cdcdcd;
                margin: 20px 50px 20px 50px;
                padding: 1px 0 10px;
                border-radius: 4px;
            }

            .messages .header {
                border-bottom: 1px solid #e5e5e5;
                background-color: #f5f5f5;

            }

            .messages .header > div {
                padding: 5px;
            }

            .messages .footer {

            }

            .messages .body {
                padding: 5px;
            }

            .content-message {
            }

            .send-message {
                margin-left: 50px;
            }
        </style>


    </div>
</div>
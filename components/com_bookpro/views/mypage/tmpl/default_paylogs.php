<?php
$app = JFactory::getApplication();
$input = $app ->input;


AImporter::model('paylogs');
$modelPaylogs = new BookProModelPayLogs();
$datas = $modelPaylogs->getData();

$obj = &$modelPaylogs->getObject();
$order_id = ARequest::getUserStateFromRequest('order_id', '', 'int');
if($order_id){
    AImporter::model('order');
    $modelOrder = new BookProModelOrder();
    $modelOrder->setId($order_id);
    $this-> order = $modelOrder->getObject();
    //var_dump( $this-> order ->order_number);
}




?>

<div class="span10">
    
    <strong><?php echo JText::_('Order Number').': '.$this-> order ->order_number;?></strong>,
    <strong><?php echo JText::_(' Order Total').': '.$this-> order ->total;?></strong>
    
    <h3 class="titlePage"><?php echo JText::_('PayMent List'); ?></h3>
        <div id="editcell">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th><?php echo JText::_("COM_BOOKPRO_PAYMENT_LOG_TITLE"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_FIELD_PAYMENT_LOG_GATEWAY"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_FIELD_PAYMENT_LOG_AMOUNT"); ?></th>
                        <th><?php echo JText::_("JGLOBAL_FIELD_CREATED_LABEL"); ?></th> 
                    </tr>
                </thead>
                <tfoot>

                </tfoot>
                <tbody>
                    <?php if (count($datas) == 0) { ?>
                        <tr>
                            <td colspan="13" class="emptyListInfo"><?php echo JText::_('No payment.'); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <?php for ($i = 0; $i < count($datas); $i++) { ?>
                            <?php $subjectdatas = $datas[$i]; ?>
                            <td><?php echo $subjectdatas->title ?></td>
                            <td><?php echo $subjectdatas->gateway ?></td>
                            <td><?php echo $subjectdatas->amount ?></td>
                            <td><?php echo $subjectdatas->created ?></td>
                            
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="option" value="<?php echo OPTION; ?>" />
        <input	type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" /> 
        <input type="hidden" name="reset"	value="0" /> 
        <input type="hidden" name="cid[]"	value="" />
        <input type="hidden" name="boxchecked" value="0" /> 
        <input	type="hidden" name="filter_order" value="<?php echo $order; ?>" /> 
        <input	type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>" />
        <input type="hidden" name="controller"	value="<?php echo CONTROLLER_ORDER; ?>" />
        <?php echo JHTML::_('form.token'); ?>
</div>





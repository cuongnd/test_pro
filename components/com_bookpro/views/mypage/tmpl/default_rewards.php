<?php
$model = new BookProModelCustomer();
$model->setIdByUserId();
$customer = $model->getObject();

$point=$model->getrewards($customer->id);

?>
<style type="text/css">
    table{
        background: none ;
    }
</style>
<div class="span10">
    <h2 class="titlePage"><?php echo JText::_('COM_BOOKPRO_REWARDS'); ?></h2>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
			<b><?php echo JText::_('COM_BOOKPRO_POINT')?></b>:<?php echo $point->point ?>
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
    </form>

</div>


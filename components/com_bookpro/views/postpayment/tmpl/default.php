<?php
    defined('_JEXEC') or die('Restricted access');
    AImporter::helper('currency');

?>

<div class="row-fluid">
    <div class="span12">

        <h3><?php echo JText::_( "COM_BOOKPRO_CHECKOUT_RESULTS" ); ?></h3>

        <a href="<?php echo JUri::base().'index.php?option=com_bookpro&controller=order&task=detail&order_id='.$this->order->id ?>">Order detail</a>
        <?php 
            if($this->order->pay_status="SUCCESS")
            {
                echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_SUCCESS_MSG');
            }
            else 
            {
                echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_PEDING_MSG');
            }

        ?>

    </div>
</div>

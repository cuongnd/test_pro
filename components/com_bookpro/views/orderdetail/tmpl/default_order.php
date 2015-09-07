<?php 
    defined( '_JEXEC' ) or die( 'Restricted access' );


?>
<h2>
    <?php echo JText::_('COM_BOOKPRO_ORDER_SUMARY'); ?>
</h2>



<table class="table table-bordered">
    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?>:
        </th>
        <td><span class="total"><?php echo $this->order->order_number; ?></span></td>

    </tr>
    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME'); ?>:
        </th>
        <td><?php echo $this->order->firstname. ' '.$this->order->lastname; ?></td>

    </tr>

    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>:
        </th>
        <td><?php echo $this->order->email	?></td>
    </tr>

    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>:
        </th>
        <td><?php echo $this->order->telephone;?></td>

    </tr>


    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?>:
        </th>
        <td><?php echo CurrencyHelper::formatprice($this->order->total); ?></td>
    </tr>
    <tr>
        <th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>:
        </th>
        <!--  -->
        <td><?php 
                echo '<span class="label label-warning">'.JText::_('COM_BOOKPRO_PAYMENT_STATUS_'.$this->order->pay_status).'</span>&nbsp;';

                if($this->order->pay_status!='SUCCESS' ) {
                    echo JHtml::link(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$this->order->id.'&'.JSession::getFormToken().'=1', JText::_('COM_BOOKPRO_MAKE_PAYMENT'),'class="btn"');
            }?>
            
        </td>
    </tr>

    <tr>

        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>:
        </th>
        <td><?php echo JHtml::_('date',$this->order->created); ?></td>
    </tr>
</table>

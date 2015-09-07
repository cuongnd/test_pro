<?php
defined('_JEXEC') or die ('Restricted access');
AImporter::model('tourpackage', 'tour', 'orderinfos', 'passengers', 'addons', 'roomtypes', 'packagerate');
AImporter::helper('tour');
JHtml::_('behavior.calendar');
$db = JFactory::getDbo();
$infomodel = new BookProModelOrderinfos ();
$infomodel->init(array(
    'order_id' => $this->order->id
));
$this->orderinfo = $infomodel->getData();

for ($i = 0; $i < count($this->orderinfo); $i++) {
    if ($this->orderinfo [$i]->type = "TOUR") {
        $info = $this->orderinfo [$i];
        unset ($this->orderinfo [$i]);
        break;
    }
}

$this->assignRef("info", $info);


?>

<h2>
    <span><?php echo JText::_("COM_BOOKPRO_BOOKING_INFORMATION") ?> </span>
</h2>
<?php
$app = JFactory::getApplication();
$input = $app->input;
?>

<form id = "tourBookForm" name = "tourBookForm" action = "index.php" method = "post">
    <div class = "mainfarm">
        <div style = "width: 100%; overflow: hidden" class = "row-fluid">
            <div style = "float: left;width: 50%" class = "span6">
                <div class = "title" style = "padding: 10px"> <?php echo JText::_('COM_BOOKPRO_SUMMARY') ?> </div>
                <table border = "1" style = "text-align: left;width: 80%" cellpadding = "5" cellspacing = "0">
                    <tbody>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?> </th>
                        <td><?php echo $this->order->order_number ?> </td>
                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?>:
                        </th>
                        <td><?php echo CurrencyHelper::formatprice($this->order->total); ?></td>
                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;">
                            <?php echo JText::_('COM_BOOKPRO_TOUR_TITLE') ?>
                        </th>
                        <td>
                            <a
                                href = "<?php echo JURI::root() . 'index.php?option=com_bookpro&controller=tour&view=tour&id=' . $this->tour->id ?>"><?php echo $this->tour->title ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_TOUR_CODE') ?>
                        </th>
                        <td>
                            <?php echo $this->tour->code ?>
                        </td>
                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE') ?>
                        </th>
                        <td><?php echo $this->package->title ?>
                        </td>
                    </tr>

                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_START_DATE') ?>
                        </th>
                        <td>
                            <?php echo JHtml::_('date', $this->info->start) ?>
                        </td>
                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_FINISH_DATE') ?> </th>
                        <td> <?php echo JHtml::_('date', $this->info->end) ?> </td>
                    </tr>

                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>:
                        </th>
                        <td><?php echo '<span class="label label-warning">' . JText::_('COM_BOOKPRO_PAYMENT_STATUS_' . $this->order->pay_status) . '</span>&nbsp;'; ?> </td>
                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>:</th>
                        <td><?php echo JHtml::_('date', $this->order->created); ?></td>
                    </tr>


                    </tbody>
                </table>
            </div>
            <div style = "float: left;width: 50%" class = "span6">
                <div class = "title" style = "padding: 10px">
                    <?php echo JText::_('COM_BOOKPRO_OPRATION') ?>
                </div>
                <table border = "1" style = "text-align: left;width: 80%" cellpadding = "5" cellspacing = "0">
                    <tbody>


                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>:
                        </th>
                        <td><?php echo $this->customer->firstname; ?></td>

                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>:
                        </th>
                        <td><?php echo $this->customer->lastname; ?></td>

                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_COUNTRY'); ?>:
                        </th>
                        <td><?php echo $this->customer->country_id; ?></td>

                    </tr>
                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>:
                        </th>
                        <td><?php echo $this->customer->email ?></td>
                    </tr>

                    <tr>
                        <th style = "background: #444;color: #fff;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>:
                        </th>
                        <td><?php echo $this->customer->telephone; ?></td>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>



        <div><a href = "index.php?option=com_bookpro&controller=order&task=detail&order_id=<?php echo $this->order->id ?>"><?php echo JText::_('Order Detail') ?></a> </div>


    </div>

    <input type = "hidden" name = "option" value = "com_bookpro"/>
    <input
        type = "hidden" name = "controller" value = "order"/> <input type = "hidden"
                                                                     name = "task" value = "updateorder"/> <input type = "hidden"
                                                                                                                  name = "order_id" value = "<?php echo $this->order->id; ?>"/> <input
        type = "hidden" name = "<?php echo $this->token ?>" value = "1"/>
</form>




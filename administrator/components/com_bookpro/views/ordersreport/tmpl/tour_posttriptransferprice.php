<h6 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_POST_TRIP_TRANSFER') ?></h6>
<table class="table table-bordered">
    <tr>

        
        <th><?php echo JText::_('COM_BOOKPRO_FLIGHT_NUMBER') ?>
        </th>
        <th><?php echo JText::_('COM_BOOKPRO_ARRIVAL_DATE_TIME') ?>
        </th>
    </tr>                   
    <?php foreach ($this->passenger->post_airport_transfer as $airport_transfer) { ?>
        <tr>
            <td><?php echo $airport_transfer->flightnumber; ?></td>
            <td><?php echo JFactory::getDate($airport_transfer->arrival_date_time)->format('l, j F Y h:i A'); ?></td>
        </tr>
    <?php } ?>

</table>
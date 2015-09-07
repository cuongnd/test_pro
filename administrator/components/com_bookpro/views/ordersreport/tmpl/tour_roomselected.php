<h6 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_ROOM_SELECTED') ?></h6>
<?php $k = 0 ?>
<table class="table table-bordered">
        <tr>
            <td colspan="2">
                <table style="width: 100%">
                    <tr>
                    <th><?php echo JText::_('COM_BOOKPRO_ROOMTYPE') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_CHECKIN') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_CHECKOUT') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_HOTEL') ?>
                        </th>
                    </tr>
                    <?php
                    foreach ($this->passenger->nonetrip as $room) {
                        ?>
                        <tr>
                           <td> <?php echo $room->title?></td>
                            <td> <?php echo JHtml::_('date', $room->checkin) ?></td>
                            <td><?php echo JHtml::_('date', $room->checkout) ?></td>
                            <td><?php echo $room->hotel ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
</table>
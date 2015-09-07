<?php
AImporter::model('addons');
$modeltouraddone = new BookProModelAddons();
$listadditionnaltrip = $modeltouraddone->getItems();
$listadditionnaltrip = JArrayHelper::pivot($listadditionnaltrip, 'id');
?>
<h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_ADDITIONNALTRIP') ?></h3>
<?php $k = 0; ?>
<table class="table table-bordered">
    <?php foreach ($this->list_addonpassenger as $addonpassenger) { ?>
        <tr>
           
            <td><b><?php echo $listadditionnaltrip[$addonpassenger[0]->addone_id]->title ?></b>

            </td>
        </tr>
        <tr>
            <td >
                <table style="width: 100%">
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME') ?>
                        </th>

                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_ADDRESS') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT') ?>
                        </th>
                    </tr>
                    <?php
                    $m = 0;
                    $i=1;
                    foreach ($addonpassenger as $passenger) {
                        
                        ?>
                        <tr>
                            <?php
                            $passenger = $this->pivot_passengers[$passenger->passenger_id];
                            $fullname = $passenger->firstname . ' ' . $passenger->lastname;
                            ?>
                            <td><?php echo ($i++) . '.' . $passenger->firstname . ' ' . $passenger->lastname . ' (' . ($passenger->gender ? "Male" : "Female") . ')'; ?></td>
                            <td><?php echo JHtml::_('date', $passenger->birthday, "d-m-Y"); ?></td>
                            <td><?php echo $passenger->email; ?></td>
                            <td><?php echo $passenger->phone1; ?></td>
                            <td><?php echo $passenger->address; ?></td>
                            <td><?php echo $passenger->passport; ?></td>

                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
    <?php } ?>

</table>
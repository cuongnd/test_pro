<?php
AImporter::helper('currency');
?>
<table>
    <thead>

    <tr>
        <th><?php echo JText::_('Vehicle') ?></th>
        <th class="header-hide"></th>
        <th><?php echo JText::_('Departure') ?></th>
        <th><?php echo JText::_('Arrival') ?></th>
        <th><?php echo JText::_('Trip/Time') ?></th>
        <th class="header-hide"></th>
    </tr>
    </thead>
    <tbody>

    <?php if(count($this->listBusTrip)){ ?>

    <?php
        $i=0;
        foreach($this->listBusTrip as $busTrip){
            ?>
            <tr data-i="<?php echo $i ?>" style="<?php echo $i>$this->numberItemOnOnePage-1?'display: none':'' ?>" class="row-<?php echo $i ?>">
                <td><img class="item-image" src="<?php echo JUri::root() ?><?php echo $busTrip->bus_image ?>"></td>
                <td>
                    <?php if(count($busTrip->facilities)){ ?>

                        <ul>
                        <?php foreach($busTrip->facilities as $facility){ ?>
                            <li><img src="<?php echo JUri::root() ?><?php echo $facility->image ?>"> </li>
                        <?php } ?>
                        </ul>
                    <?php } ?>
                </td>
                <td>
                    <div class="row-fluid"><?php echo $busTrip->dest_from_parent_title ?></div>
                    <div class="row-fluid"><?php echo $busTrip->dest_from_title ?></div>
                </td>
                <td>
                    <div class="row-fluid"><?php echo $busTrip->dest_to_parent_title ?></div>
                    <div class="row-fluid"><?php echo $busTrip->dest_to_title ?></div>
                </td>
                <td>
                    <div class="row-fluid  <?php echo $busTrip->roundtrip?' round-trip ':' one-way-icon ' ?>"><?php echo $busTrip->duration ?></div>
                </td>
                <td>
                    <div class="row-fluid price"><?php echo CurrencyHelper::formatprice($busTrip->event->text); ?></div>
                    <div class="row-fluid  select select-bustrip"><div class=" radio"><label>  select   <input name="event_id" value="<?php echo $busTrip->event->id ?>" class="pull-right select-this-bustrip"  type="radio"></label></div></div>
                </td>

            </tr>
            <tr data-i="<?php echo $i ?>" style="<?php echo $i>$this->numberItemOnOnePage-1?'display: none':'' ?>" class="row-<?php echo $i ?>">
                <td class="footer-item" colspan="6">

                    <div class="float-left"><?php echo $busTrip->bus_title ?></div>
                    <div class="float-right"><span class="trip-detail"><?php echo JText::_('trip detail') ?></span><span class="price-rule"><?php echo JText::_('price rule') ?></span></div>
                </td>
            </tr>
        <?php $i++ ?>
        <?php } ?>
    <?php }else{ ?>
        <tr>
            <td colspan="6" style="text-align: center">
                <?php echo JText::_('Cannot found, please filter or search again') ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

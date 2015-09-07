<?php
AImporter::helper('currency');
$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
$cart->load();
?>
<?php if(count($this->listBustripAddOne)){ ?>
<div class="row-fluid traveller-add-ons">
    <div class="row-fluid header"><h3><?php echo JText::_('Optional add ons') ?></h3></div>
    <div class="sub-wrapper-content">
        <table>
            <thead>
                <tr>
                    <th><?php echo JText::_('Add') ?></th>
                    <th><?php echo JText::_('Excursion') ?></th>
                    <th><?php echo JText::_('Detail') ?></th>
                    <th><?php echo JText::_('Duration') ?></th>
                    <th><?php echo JText::_('Price') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php for($i=0;$i<count($this->listBustripAddOne);$i++){ ?>
            <?php
                $addOne=$this->listBustripAddOne[$i];
            ?>
                <tr>
                    <td><div data-addone-id="<?php echo $addOne->id ?>" class="add icon-checked <?php echo in_array($addOne->id,$cart->addOne)?'':' uncheck ' ?>">&nbsp</div></td>
                    <td><?php echo $addOne->title ?></td>
                    <td><img data-addone-id="<?php echo $addOne->id ?>"   class="add-one-detail" src="<?php echo JUri::root() ?>/components/com_bookpro/assets/images/icons/icon-note.png"></td>
                    <td>5 hrs</td>
                    <td><?php echo CurrencyHelper::displayPrice($addOne->price,0) ?></td>
                </tr>
                <tr  class="add-one-detail add-one-detail-<?php echo $addOne->id ?>">
                    <td colspan="5"><?php echo $addOne->description ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>


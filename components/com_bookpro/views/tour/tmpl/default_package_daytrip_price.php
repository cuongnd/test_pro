<?php

$listtourpackagerates=$this->listtourpackagerates[0];
$listmin_person = array();
foreach ($listtourpackagerates as $tourpackagerates) {
    if (!in_array($tourpackagerates->min_person, $listmin_person))
        array_push($listmin_person, $tourpackagerates->min_person);
}
?>
<div class="content_div2_date_private">
    <table class="table content_table_tours_class">
        <?php if (count($listtourpackagerates)) { ?>
            <thead style="background:#95a5a5; color:#fff;">
                <tr>
                    <th style="padding-left:10px; font-size:14px;">PASSENGER</th>
                    <?php foreach ($listmin_person as $array_person_item) { ?>
                        <th> <?php echo $array_person_item ?> <?php echo JText::_($array_person_item==1?'COM_BOOKPRO_PER':'COM_BOOKPRO_PERS') ?>.</th>
                    <?php } ?>

                    <th colspan="2"> AMEND DATE</th>
                </tr>
            </thead>
        <?php } ?>
        <tbody style="background:#f7f7f7;">
            <?php if (count($listtourpackagerates)) { ?>
                <tr style="border-bottom:2px solid #fff!important;">
                    <td>PRIVATE GROUP</td>
                    <?php foreach ($listtourpackagerates as $tourpackagerates) { ?>
                        <?php foreach ($listmin_person as $array_person_item) { ?>
                            <?php if ($tourpackagerates->min_person == $array_person_item) { ?>
                                <td><?php echo CurrencyHelper::formatprice($tourpackagerates->adult_promo ? $tourpackagerates->adult_promo : $tourpackagerates->adult) ?>  </td>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <td><input class="inputbox required checkin" type="hidden" name="checkin" value="<?php echo JRequest::getVar('checkin'); ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CHECKIN'); ?>" /></td>


                    <td><a class="bookingtourpackage" data="{&quot;packagetype_id&quot;:10,&quot;tour_id&quot;:<?php echo JRequest::getVar('id') ?>,&quot;stype&quot;:&quot;private&quot;}" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></a> </td>
                </tr>
            <?php } ?>
            <tr style="border-bottom:2px solid #fff!important;">
                <td>JOINT GROUP</td>
                <td colspan="<?php echo count($listmin_person) ?>"><?php echo $this->listtourpackagerates[1]->adult?CurrencyHelper::formatprice($this->listtourpackagerates[1]->adult):'unavaible' ?>  </td>
                <td><input class="inputbox required checkin" type="hidden" name="checkin" value="<?php echo JRequest::getVar('checkin'); ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CHECKIN'); ?>" /></td>


                <td><a class="bookingtourpackage" data="{&quot;packagetype_id&quot;:10,&quot;tour_id&quot;:<?php echo JRequest::getVar('id') ?>,&quot;stype&quot;:&quot;shared&quot;}" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></a> </td>
            </tr>
        </tbody>
    </table>

</div>

<style type="text/css">


</style>

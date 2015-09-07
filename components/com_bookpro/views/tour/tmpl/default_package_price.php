<?php
AImporter::model('packagetypes','tourpackages');
AImporter::helper('currency');
$list = array();
$list['order'] = 'ordering';
$list['order_Dir'] = 'asc';
$model_packagetype = new BookProModelPackageTypes();
$model_packagetype->init($list);
$now = JFactory::getDate(JRequest::getVar('checkin'));

$checkin = JFactory::getDate($now->format('Y-m-d'));

AImporter::model('tour');
$module_tour = new BookProModelTour();
$module_tour->setId(JRequest::getVar('id'));
$tour = $module_tour->getObject();
$db = JFactory::getDbo();


$arr_person = array();
$packagetypes = $model_packagetype->getData();

$tour_id = JRequest::getVar('id');

foreach ($packagetypes as $key_packagetype => $packagetype) {
    $lists = array();
    $lists['tour_id'] = $tour_id;
    $lists['packagetype_id'] = $packagetype->id;
    $modle_tourpackage = new BookProModelTourPackages();
    $modle_tourpackage->init($lists);
    $tourpackages = $modle_tourpackage->getData();
    foreach ($tourpackages as $key => $tourpackage) {
        $query = $db->getQuery(true);
        $query->select('packagerate.adult');
        $query->from('#__bookpro_packagerate as packagerate');
        $query->where('packagerate.date=' . $db->quote($checkin->toSql()));
        $query->where('packagerate.tourpackage_id=' . ($tourpackage->id ? $tourpackage->id : 0));
        $db->setQuery($query);
        
        $result = $db->loadResult();
        $tourpackages[$key]->price = $result;
        if (!in_array($tourpackage->min_person, $arr_person)) {
            array_push($arr_person, $tourpackage->min_person);
        }
    }
    $packagetypes[$key_packagetype]->tourpackages = $tourpackages;
}
$arr_person = array_reverse($arr_person);
?>
<table class="table content_table_tours_class">
    <thead style="background:#95a5a5; color:#fff;">
        <tr>
            <th style="padding-left:10px; font-size:14px;">TOUR CLASS</th>
            <?php foreach ($arr_person as $array_person_item) { ?>
                <th> <?php echo $array_person_item ?> <?php echo JText::_('COM_BOOKPRO_PER') ?>.</th>
            <?php } ?>

            <th colspan="2"> AMEND DATE</th>
        </tr>
    </thead>
    <tbody style="background:#f7f7f7;">
        <?php foreach ($packagetypes as $packagetype) { ?>

            <tr>
                <td><?php echo $packagetype->title ?></td>
                <?php
                foreach ($arr_person as $array_person_item) {
                    foreach ($packagetype->tourpackages as $tourpackage) {
                        $price = 0;
                        if ($tourpackage->min_person == $array_person_item) {
                            $price = $tourpackage->price;
                            break;
                        }
                        ?>

                    <?php } ?>
                    <td><?php echo CurrencyHelper::formatprice($price) ? CurrencyHelper::formatprice($price) : 'unable' ?>  </td>
                <?php } ?>
                <td><input class="inputbox required checkin" type="hidden" name="checkin" value="<?php echo JRequest::getVar('checkin'); ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CHECKIN'); ?>" /></td>


                <td><a class="bookingtourpackage" data="{&quot;packagetype_id&quot;:<?php echo $packagetype->id ?>,&quot;tour_id&quot;:<?php echo JRequest::getVar('id') ?>}" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></a> </td>
            </tr>
        <?php } ?>

    </tbody>
</table>
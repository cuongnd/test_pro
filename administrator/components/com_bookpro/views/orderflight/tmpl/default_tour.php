
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: tour.php 113 2012-09-07 08:13:19Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');
AImporter::model('tourpackage', 'tour', 'categories');
$pmodel = new BookProModelTourPackage();
$pmodel->setId($this->orderinfo[0]->obj_id);
$price = $pmodel->getObject();
$jlang = JFactory::getLanguage();
$jlang->load('com_bookpro', JPATH_SITE, 'en-GB', true);
$jlang->load('com_bookpro', JPATH_SITE, $jlang->getDefault(), true);
JToolBarHelper::title($this->order->id ? JText::_('COM_BOOKPRO_ORDER_EDIT') : JText::_('COM_BOOKPRO_ORDER_EDIT'), 'object');

AImporter::model('tourpackage', 'tour', 'orderinfos', 'passengers', 'addons', 'roomtypes', 'packagerate');
AImporter::helper('tour');
JHtml::_('behavior.calendar');
$db = JFactory::getDbo();
$infomodel = new BookProModelOrderinfos();
$infomodel->init(array('order_id' => $this->order->id));
$this->orderinfo = $infomodel->getData();

for ($i = 0; $i < count($this->orderinfo); $i++) {
    if ($this->orderinfo[$i]->type = "TOUR") {
        $info = $this->orderinfo[$i];
        unset($this->orderinfo[$i]);
        break;
    }
}

$this->assignRef("info", $info);
$modeltouraddone = new BookProModelAddons();

$list_addone = $modeltouraddone->getItems();

$pivot_list_addone = JArrayHelper::pivot($list_addone, 'id');
$this->assignRef("pivot_list_addone", $pivot_list_addone);


$rtmodel = new BookProModelRoomTypes();
$listroomtype = $rtmodel->getData();
$pivot_listroomtype = JArrayHelper::pivot($listroomtype, 'id');
$this->assignRef("pivot_listroomtype", $pivot_listroomtype);



$rooms = TourHelper::getRoomType($this->order->id);
$query = $db->getQuery(true);

$packagerate = new BookProModelPackageRate();
$packagerate->setId($info->obj_id);
$this->packagerate = $packagerate->getObject();

$tourpackageModel = new BookProModelTourPackage();
$tourpackageModel->setId($this->packagerate->tourpackage_id);
$this->package = $tourpackageModel->getObject();


$tourModel = new BookProModelTour();
$tourModel->setId($this->package->tour_id);
$this->tour = $tourModel->getObject();

$passengersModel = new BookProModelPassengers();
$passengersModel->init(array('order_id' => $this->order->id, 'order_Dir' => 'ASC'));
$passengers = $passengersModel->getData();
$pivot_passengers = JArrayHelper::pivot($passengers, 'id');
//echo '<pre>';print_r($pivot_passengers);

$listidpass = array();
foreach ($passengers as $passenger) {
    $listidpass[] = $passenger->id;
}
$listidpass = implode(',', $listidpass);
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_roomtypepassenger as o_roomtypepassenger');
$query->where('passenger_id IN(' . $listidpass . ')');
$query->where('o_roomtypepassenger.order_id=' . $this->order->id);

$query->order('type,grouproom');
$db->setQuery($query);

$listpassenger = $db->loadObjectList();

$bookroom = array();
$bookroom_pre_trip_acommodaton = array();
$bookroom_post_trip_acommodaton = array();
foreach ($listpassenger as $passenger) {
    switch ($passenger->type) {
        case '0':
            $bookroom[] = $passenger;
            break;
        case 'pre_trip_acommodaton':
            $bookroom_pre_trip_acommodaton[] = $passenger;
            break;
        case 'post_trip_acommodaton':
            $bookroom_post_trip_acommodaton[] = $passenger;
            break;
    }
}
$grouproom = array();
$list_bookroom = array();
foreach ($bookroom as $room) {
    if (!in_array($room->grouproom, $grouproom)) {
        array_push($grouproom, $room->grouproom);
    }
    $key = array_search($room->grouproom, $grouproom);
    $list_bookroom[$key][] = $room;
}


$this->assign('list_bookroom', $list_bookroom);

$grouproom = array();
$list_post_trip_acommodaton = array();
foreach ($bookroom_post_trip_acommodaton as $trip_acommodaton) {
    if (!in_array($trip_acommodaton->grouproom, $grouproom)) {
        array_push($grouproom, $trip_acommodaton->grouproom);
    }
    $key = array_search($trip_acommodaton->grouproom, $grouproom);
    $list_post_trip_acommodaton[$key][] = $trip_acommodaton;
    $pivot_passengers[$trip_acommodaton->passenger_id]->post_trip_acommodaton = $trip_acommodaton;
}

$this->assign('list_post_trip_acommodaton', $list_post_trip_acommodaton);
$grouproom = array();
$list_pre_trip_acommodaton = array();
foreach ($bookroom_pre_trip_acommodaton as $trip_acommodaton) {
    if (!in_array($trip_acommodaton->grouproom, $grouproom)) {
        array_push($grouproom, $trip_acommodaton->grouproom);
    }
    $key = array_search($trip_acommodaton->grouproom, $grouproom);
    $list_pre_trip_acommodaton[$key][] = $trip_acommodaton;
    $pivot_passengers[$trip_acommodaton->passenger_id]->pre_trip_acommodaton = $trip_acommodaton;
}
$this->assign('list_pre_trip_acommodaton', $list_pre_trip_acommodaton);

$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_transferpassenger as o_transferpassenger');
$query->where('passenger_id IN(' . $listidpass . ')');
$db->setQuery($query);
$listtransferpassenger = $db->loadObjectList();

$post_airport_transfer = array();
$pre_airport_transfer = array();
foreach ($listtransferpassenger as $passenger) {
    switch ($passenger->type) {

        case 'post_airport_transfer':
            $post_airport_transfer[] = $passenger;
            $pivot_passengers[$passenger->passenger_id]->post_airport_transfer = $passenger;
            break;
        case 'pre_airport_transfer':
            $pre_airport_transfer[] = $passenger;
            $pivot_passengers[$passenger->passenger_id]->pre_airport_transfer = $passenger;
            break;
    }
}
$this->assign('post_airport_transfer', $post_airport_transfer);
$this->assign('pre_airport_transfer', $pre_airport_transfer);

$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_tourpassenger as o_tourpassenger');
$query->where('passenger_id IN(' . $listidpass . ')');
$db->setQuery($query);
$listtourpassenger = $db->loadObjectList('passenger_id');

$this->assign('listtourpassenger', $listtourpassenger);



$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_addonpassenger as o_addonpassenger');
$query->where('o_addonpassenger.passenger_id IN(' . $listidpass . ')');
$query->order('addone_id');
$db->setQuery($query);
$listaddonpassenger = $db->loadObjectList();

$grouproom = array();
$list_addonpassenger = array();


foreach ($listaddonpassenger as $addonpassenger) {
    if (!in_array($addonpassenger->addone_id, $grouproom)) {
        array_push($grouproom, $addonpassenger->addone_id);
    }
    $key = array_search($addonpassenger->addone_id, $grouproom);
    $list_addonpassenger[$key][] = $addonpassenger;

    $pivot_passengers[$addonpassenger->passenger_id]->additionnaltrip_ids[] = $addonpassenger;
}

$this->assign('list_addonpassenger', $list_addonpassenger);

$this->assign('pivot_passengers', $pivot_passengers);
$this->assign('passengers', $passengers);


$tourModel = new BookProModelTour();
$tourModel->setId($price->tour_id);
$tour = $tourModel->getObject();
$link = JRoute::_(ARoute::edit(CONTROLLER_TOUR, $tour->id));
$config = AFactory::getConfig();


$catmodel = new BookProModelCategories();
$lists = array('type' => 9);
$catmodel->init($lists);
$list = $catmodel->getData();
$pickup = AHtml::getFilterSelect('location', JText::_('COM_BOOKPRO_SELECT_PICKUP'), $list, $this->orderinfo[0]->location, false, '', 'id', 'title');
?>
<div class="mainfarm">
    <div class="span12">
        <div class="span6">
            <div class="title" style="padding: 10px">
                <?php echo JText::_('COM_BOOKPRO_SUMMARY') ?>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?>
                        </th>
                        <td><?php echo $this->order->order_number ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_TOUR_TITLE') ?>
                        </th>
                        <td><a
                                href="<?php echo JURI::root() . 'index.php?option=com_bookpro&controller=tour&view=tour&id=' . $this->tour->id ?>"><?php echo $this->tour->title ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_TOUR_CODE') ?>
                        </th>
                        <td>
                            <?php echo $this->tour->code ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE') ?>
                        </th>
                        <td><?php echo $this->package->title ?>
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_START_DATE') ?>
                        </th>
                        <td>
                            <?php echo JHtml::_('date', $this->info->start) ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_FINISH_DATE') ?>
                        </th>
                        <td>
                            <?php echo JHtml::_('date', $this->info->end) ?>
                        </td>
                    </tr>


                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME'); ?>:
                        </th>
                        <td><?php echo $this->customer->firstname . ' ' . $this->customer->lastname; ?></td>

                    </tr>

                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>:
                        </th>
                        <td><?php echo $this->customer->email ?></td>
                    </tr>

                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>:
                        </th>
                        <td><?php echo $this->customer->telephone; ?></td>

                    </tr>


                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?>:
                        </th>
                        <td><?php echo CurrencyHelper::formatprice($this->order->total); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="span6">
            <div class="title" style="padding: 10px">
                <?php echo JText::_('COM_BOOKPRO_OPRATION') ?>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>:
                        </th>
                        <!--  -->
                        <td><?php echo $this->getPayStatusSelect($this->order->pay_method); ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_STATUS'); ?>:
                        </th>
                        <td><?php echo $this->getOrderStatusSelect($this->order->order_status); ?>

                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ASIGN_SALE'); ?>:
                        </th>
                       
                        <td><?php echo $this->getListSale($this->order->sale_id); ?>

                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_SEND_EMAIL'); ?>:
                        </th>
                        <td><input type="button" class="btn sendemailagain" value="<?php echo Jtext::_('COM_BOOKPRO_SENDEMAIL') ?>"/>

                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>:
                        </th>
                        <td><?php echo JHtml::_('date', $this->order->created); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="span12">
        <div class="row-fluid">

            <?php
            $this->setlayout('tour');
            $this->passengers = $passengers;
            echo $this->loadTemplate('listpassenger');
            ?>
            <div class="order-overview row-fluid">
                <?php if (count($this->list_bookroom)) { ?>
                    <div class="roomselected ">
                        <?php echo $this->loadTemplate("roomselected") ?>
                    </div>
                <?php } ?>
                <?php if (count($this->list_pre_trip_acommodaton)) { ?>
                    <div class="tripprice pre_trip_acommodaton ">
                        <?php echo $this->loadTemplate("pretripprice") ?>
                    </div>
                <?php } ?>
                <?php if (count($this->list_post_trip_acommodaton)) { ?>
                    <div class="tripprice post_trip_acommodaton ">
                        <?php echo $this->loadTemplate("posttripprice") ?>
                    </div>
                <?php } ?>
                <?php if (count($this->pre_airport_transfer)) { ?>
                    <div class="triptransfer pre_airport_transfer ">
                        <?php echo $this->loadTemplate("pretriptransferprice") ?>
                    </div>
                <?php } ?>
                <?php if (count($this->post_airport_transfer)) { ?>
                    <div class="triptransfer post_airport_transfer ">
                        <?php echo $this->loadTemplate("posttriptransferprice") ?>
                    </div>
                <?php } ?>
                <?php if (count($this->list_addonpassenger)) { ?>
                    <div class="additionnaltripprice ">
                        <?php echo $this->loadTemplate("additionnaltripprice") ?>
                    </div>
                <?php } ?>

            </div>
            <?php
            echo $this->loadTemplate('default');
            ?>
        </div>
    </div>


</div>



<input type="hidden" name="order_id" value="<?php echo $this->order->id; ?>" />
<?php echo FormHelper::bookproHiddenField(array('controller' => 'order', 'task' => '', 'Itemid' => JRequest::getInt('Itemid'))) ?>











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
$modeltouraddone = new BookProModelAddons ();

$list_addone = $modeltouraddone->getItems();

$pivot_list_addone = JArrayHelper::pivot($list_addone, 'id');
$this->assignRef("pivot_list_addone", $pivot_list_addone);

$rtmodel = new BookProModelRoomTypes ();
$listroomtype = $rtmodel->getData();
$pivot_listroomtype = JArrayHelper::pivot($listroomtype, 'id');
$this->assignRef("pivot_listroomtype", $pivot_listroomtype);

$rooms = TourHelper::getRoomType($this->order->id);
$query = $db->getQuery(true);

$packagerate = new BookProModelPackageRate ();
$packagerate->setId($info->obj_id);
$this->packagerate = $packagerate->getObject();

$tourpackageModel = new BookProModelTourPackage ();
$tourpackageModel->setId($this->packagerate->tourpackage_id);

$this->package = $tourpackageModel->getObject();

$tourModel = new BookProModelTour ();
$tourModel->setId($this->package->tour_id);
$this->tour = $tourModel->getObject();

$this->list_destination_of_tour = $tourModel->getDestination(($this->tour->id ? $this->tour->id : 0));

$passengersModel = new BookProModelPassengers ();
$passengersModel->init(array(
    'order_id' => $this->order->id,
    'order_Dir' => 'ASC'
));
$passengers = $passengersModel->getData();

$pivot_passengers = JArrayHelper::pivot($passengers, 'id');
// echo '<pre>';print_r($pivot_passengers);

$listidpass = array();
foreach ($passengers as $passenger) {
    $listidpass [] = $passenger->id;
}
$listidpass = implode(',', $listidpass);
$query = $db->getQuery(true);
$query->select('o_roomtypepassenger.*,hotel.title AS hotel');
$query->from('#__bookpro_order_roomtypepassenger as o_roomtypepassenger');
$query->leftJoin('#__bookpro_hotel AS hotel ON hotel.id=o_roomtypepassenger.hotel_id');
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
        case '0' :
            $bookroom [] = $passenger;
            break;
        case 'pre_trip_acommodaton' :
            $bookroom_pre_trip_acommodaton [] = $passenger;
            break;
        case 'post_trip_acommodaton' :
            $bookroom_post_trip_acommodaton [] = $passenger;
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
    $list_bookroom [$key] [] = $room;
}

$this->assign('pivot_bookroom', JArrayHelper::pivot($bookroom, 'passenger_id'));

$this->assign('list_bookroom', $list_bookroom);

$grouproom = array();
$list_post_trip_acommodaton = array();
foreach ($bookroom_post_trip_acommodaton as $trip_acommodaton) {
    if (!in_array($trip_acommodaton->grouproom, $grouproom)) {
        array_push($grouproom, $trip_acommodaton->grouproom);
    }
    $key = array_search($trip_acommodaton->grouproom, $grouproom);
    $list_post_trip_acommodaton [$key] [] = $trip_acommodaton;
    $pivot_passengers [$trip_acommodaton->passenger_id]->post_trip_acommodaton = $trip_acommodaton;
}

$this->assign('list_post_trip_acommodaton', $list_post_trip_acommodaton);
$grouproom = array();
$list_pre_trip_acommodaton = array();
foreach ($bookroom_pre_trip_acommodaton as $trip_acommodaton) {
    if (!in_array($trip_acommodaton->grouproom, $grouproom)) {
        array_push($grouproom, $trip_acommodaton->grouproom);
    }
    $key = array_search($trip_acommodaton->grouproom, $grouproom);
    $list_pre_trip_acommodaton [$key] [] = $trip_acommodaton;
    $pivot_passengers [$trip_acommodaton->passenger_id]->pre_trip_acommodaton = $trip_acommodaton;
}
$this->assign('list_pre_trip_acommodaton', $list_pre_trip_acommodaton);

$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_transferpassenger as o_transferpassenger');
$query->where('passenger_id IN(' . $listidpass . ')');
$query->where('o_transferpassenger.order_id=' . $this->order->id);
$db->setQuery($query);
$listtransferpassenger = $db->loadObjectList();

$post_airport_transfer = array();
$pre_airport_transfer = array();
foreach ($listtransferpassenger as $passenger) {
    switch ($passenger->type) {

        case 'post_airport_transfer' :
            $post_airport_transfer [] = $passenger;
            $pivot_passengers [$passenger->passenger_id]->post_airport_transfer = $passenger;
            break;
        case 'pre_airport_transfer' :
            $pre_airport_transfer [] = $passenger;
            $pivot_passengers [$passenger->passenger_id]->pre_airport_transfer = $passenger;
            break;
    }
}
$this->assign('post_airport_transfer', $post_airport_transfer);
$this->assign('pre_airport_transfer', $pre_airport_transfer);

$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_tourpassenger as o_tourpassenger');
$query->where('passenger_id IN(' . $listidpass . ')');
$query->where('o_tourpassenger.order_id=' . $this->order->id);
$db->setQuery($query);
$listtourpassenger = $db->loadObjectList('passenger_id');

$this->assign('listtourpassenger', $listtourpassenger);

$query = $db->getQuery(true);
$query->select('*');
$query->from('#__bookpro_order_addonpassenger as o_addonpassenger');
$query->where('o_addonpassenger.passenger_id IN(' . $listidpass . ')');
$query->where('o_addonpassenger.order_id=' . $this->order->id);
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
    $list_addonpassenger [$key] [] = $addonpassenger;

    $pivot_passengers [$addonpassenger->passenger_id]->additionnaltrip_ids [] = $addonpassenger;
}

$this->assign('list_addonpassenger', $list_addonpassenger);

$this->assign('pivot_passengers', $pivot_passengers);
$this->assign('passengers', $passengers);
?>

<h2>
    <span><?php echo JText::_("COM_BOOKPRO_BOOKING_INFORMATION") ?> </span>
</h2>
<?php
$app = JFactory::getApplication();
$input = $app->input;
?>
<?php if (!$input->get('tmpl') && !$this->sendmail) { ?>
    <div>
        <a
            href = "index.php?option=com_bookpro&controller=order&task=detail&order_id=<?php echo $this->order->id ?>&tmpl=component"
            target = "_blank" class = "btn"><?php echo JText::_('COM_BOOKPRO_PRINT') ?></a>
    </div>
<?php } ?>
<form id = "tourBookForm" name = "tourBookForm" action = "index.php" method = "post">
    <div class = "mainfarm">
        <div  class = "row-fluid">
            <div  class = "span6">
                <div class = "title" style = "padding: 10px"> <?php echo JText::_('COM_BOOKPRO_SUMMARY') ?> </div>
                <table class = "table">
                    <tbody>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?>
                        </th>
                        <td><?php echo $this->order->order_number ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?>:
                        </th>
                        <td><?php echo CurrencyHelper::formatprice($this->order->total); ?></td>
                    </tr>
                    <tr>
                        <th>
                            <?php echo JText::_('COM_BOOKPRO_TOUR_TITLE') ?>
                        </th>
                        <td>
                            <a
                                href = "<?php echo JURI::root() . 'index.php?option=com_bookpro&controller=tour&view=tour&id=' . $this->tour->id ?>"><?php echo $this->tour->title ?>
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
                        <th><?php echo JText::_('COM_BOOKPRO_FINISH_DATE') ?> </th>
                        <td> <?php echo JHtml::_('date', $this->info->end) ?> </td>
                    </tr>

                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>:
                        </th>
                        <!--  -->
                        <td><?php echo '<span class="label label-warning">' . JText::_('COM_BOOKPRO_PAYMENT_STATUS_' . $this->order->pay_status) . '</span>&nbsp;'; ?> </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>:</th>
                        <td><?php echo JHtml::_('date', $this->order->created); ?></td>
                    </tr>


                    </tbody>
                </table>
            </div>
            <div class = "span6">
                <div class = "title" style = "padding: 10px">
                    <?php echo JText::_('COM_BOOKPRO_OPRATION') ?>
                </div>
                <table class = "table">
                    <tbody>


                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>:
                        </th>
                        <td><?php echo $this->customer->firstname; ?></td>

                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>:
                        </th>
                        <td><?php echo $this->customer->lastname; ?></td>

                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_COUNTRY'); ?>:
                        </th>
                        <td><?php echo $this->customer->country_id; ?></td>

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

                    </tbody>
                </table>
            </div>
        </div>

        <div class = "row-fluid">


            <div class = "row-fluid">
                <h3 style = "text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_LIST_PASSENGER') ?></h3>
                <?php
                $this->setlayout('tour');
                $this->passengers = $passengers;
                echo $this->loadTemplate('listpassenger');
                ?>
                <div class = "order-overview row-fluid">
                    <?php if (count($this->list_bookroom)) { ?>
                        <div class = "roomselected ">
                            <?php echo $this->loadTemplate("roomselected") ?>
                        </div>
                    <?php } ?>
                    <?php if (count($this->list_pre_trip_acommodaton)) { ?>
                        <div class = "tripprice pre_trip_acommodaton ">
                            <?php echo $this->loadTemplate("pretripprice") ?>
                        </div>
                    <?php } ?>
                    <?php if (count($this->list_post_trip_acommodaton)) { ?>
                        <div class = "tripprice post_trip_acommodaton ">
                            <?php echo $this->loadTemplate("posttripprice") ?>
                        </div>
                    <?php } ?>
                    <?php if (count($this->pre_airport_transfer)) { ?>
                        <div class = "triptransfer pre_airport_transfer ">
                            <?php echo $this->loadTemplate("pretriptransferprice") ?>
                        </div>
                    <?php } ?>
                    <?php if (count($this->post_airport_transfer)) { ?>
                        <div class = "triptransfer post_airport_transfer ">
                            <?php echo $this->loadTemplate("posttriptransferprice") ?>
                        </div>
                    <?php } ?>
                    <?php if (count($this->list_addonpassenger)) { ?>
                        <div class = "additionnaltripprice ">
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

    <input type = "hidden" name = "option" value = "com_bookpro"/> <input
        type = "hidden" name = "controller" value = "order"/> <input type = "hidden"
                                                                     name = "task" value = "updateorder"/> <input type = "hidden"
                                                                                                                  name = "order_id" value = "<?php echo $this->order->id; ?>"/> <input
        type = "hidden" name = "<?php echo $this->token ?>" value = "1"/>
</form>




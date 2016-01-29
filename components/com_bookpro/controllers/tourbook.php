<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class BookProControllerTourbook extends JControllerLegacy {

    public function __construct() {
        parent::__construct();
    }

    function display() {

        $vName = JRequest::getCmd('view', 'hotel');
        switch ($vName) {
            case 'hotels':
                $this->search();
                return;
            case 'hotel':
                $this->displayhotel();
                return;
            case 'hotelzzz':
                $this->displayhotel();
                return;
        }
        JRequest::setVar('view', $vName);
        parent::display();
    }

    function ajax_show_form_listpassenger() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'listpassenger');
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        echo $contents;
        exit();
    }

    function ajax_need_asign_children_for_special_room() {

        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;
        $cart->needasignchildrenforspecialroom = $input->get('needasignchildrenforspecialroom') == 1 ? 1 : 0;
        $cart->setchildrenacommodation = null;
        foreach ($cart->person as $key_listperson => $listperson) {
            if ($key_listperson == 'children') {
                foreach ($key_listperson as $key_person => $person) {
                    $cart->person->{$key_listperson}[$key_person]->setchildrenacommodation = null;
                }
            }
        }
        foreach ($cart->setroom AS $key_room => $room) {
            foreach ($room->person_sec_id as $key_person => $person_sec_id) {
                $person_sec_id = explode(':', $person_sec_id);
                if (count($person_sec_id) == 2 && $person_sec_id[0] == "children") {
                    unset($cart->setroom[$key_room]->person_sec_id[$key_person]);
                }
            }
        }
        $this->resetpriceforthistour($cart, $cart->needasignchildrenforspecialroom);

        $cart->saveToSession();
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        $respone_array = array();


        //listpassenger
        ob_start();

        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'listpassenger');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.listpassenger',
            'contents' => $contents
        );


        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'childrenacommodation');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function


        $respone_array[] = array(
            'key' => '.children_acommodation .content',
            'contents' => $contents
        );

        ob_start();
        $this->caculator_total_for_all();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'roomselected');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.roomselected'
            , 'contents' => $contents
        );

        echo json_encode($respone_array);

        exit();
    }
    public function ajax_booking_tour()
    {
        $app = JFactory::getApplication();
        $post = file_get_contents('php://input');
        $post = json_decode($post);
        echo "<pre>";
        print_r($post);
        echo "</pre>";
        die;
        die;
    }
    function ajax_show_children_acommodation() {
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;
        $list_selected = $input->getString('list_selected');

        $checkin_date = JFactory::getDate($cart->checkin_date);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__bookpro_packagerate AS packagerate');
        $query->where('packagerate.date=' . $db->quote($checkin_date->toSql()));
        $query->where('packagerate.tourpackage_id=' . $cart->package_id);
        $db->setQuery($query);
        $object_packagerate = $db->loadObject();
        //down total price
        if ($cart->setchildrenacommodation) {
            foreach ($cart->setchildrenacommodation as $childrenacommodation) {
                $person_sec_id = explode(':', $childrenacommodation->person_sec_id);
                if (count($person_sec_id) == 2) {
                    $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->total-= $childrenacommodation->price;
                    unset($cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->setchildrenacommodation);
                    unset($cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->roombooking);
                }
            }
        }
        $setchildrenacommodation = $input->get('setchildrenacommodation', array(), 'ARRAY');
        $cart->setchildrenacommodation = json_decode(json_encode($setchildrenacommodation), FALSE);
        AImporter::model('roomtypes');
        $rtmodel = new BookProModelRoomTypes();
        $listroomtype = $rtmodel->getData();
        $pivot_listroomtype = JArrayHelper::pivot($listroomtype, 'id');

        foreach ($cart->setchildrenacommodation as $childrenacommodation) {

            $childrenacommodation->price = ($childrenacommodation->needbed && $childrenacommodation->oder_room) ? $object_packagerate->extra_bed : 40;
            $childrenacommodation->list_selected = $list_selected;
            $person_sec_id = explode(':', $childrenacommodation->person_sec_id);
            $roomtype = json_decode($cart->setroom[$childrenacommodation->oder_room]->roomtype);
            $roomtype = $pivot_listroomtype[$roomtype->id];
            $childrenacommodation->roomtype_id = $roomtype->id;

            if (count($person_sec_id) == 2) {
                $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->setchildrenacommodation = $childrenacommodation;
                $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->total+= $childrenacommodation->price;
                $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->roombooking->roomtype_id = $childrenacommodation->roomtype_id;
                $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->roombooking->group_room = $childrenacommodation->oder_room - 1;
            }
        }

        $cart->saveToSession();
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'childrenacommodation');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.children_acommodation .content',
            'contents' => $contents
        );


        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'extrabedprice');
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.extrabedprice',
            'contents' => $contents
        );



        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'roomselected');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.roomselected'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);

        exit();
    }
	function form_request()
	{
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_bookpro&view=tourbook&layout=form_request');
		return;
	}
    function getdatetimebookingpick($view, $cart) {

        $app = JFactory::getApplication();
        $input = $app->input;
        $packagetype_id = $input->get('packagetype_id') ? $input->get('packagetype_id') : 0;
        $cart->packagetype_id = $packagetype_id ? $packagetype_id : $cart->packagetype_id;

        $cart->saveToSession();

        $total_adult = $input->get('adult');
        $total_teenner = $input->get('teenner');
        $total_person = $total_adult + $total_teenner;
        AImporter::model('tour');
        $model_tour = new BookProModelTour();
        $model_tour->setId($cart->tour_id ? $cart->tour_id : 0);
        $tour = $model_tour->getObject();
        $stype = $tour->daytrip ? 'daytrip' : $tour->stype;
        $date_tours = $view->{'getdate_tours_' . $stype}($cart, $total_person);
        return json_encode($date_tours);
    }

    function ajax_show_datetimebookingpick() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        echo $this->getdatetimebookingpick($view, $cart);
        exit();
    }

    function bookingtourpackage() {
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $cart->clear();
        $app = JFactory::getApplication();
        $input = $app->input;
        $cart->checkin_date = $input->get('checkin');
        $cart->checkin_date = $cart->checkin_date ? $cart->checkin_date : JFactory::getDate()->format('d-m-Y');
        $cart->packagetype_id = $input->get('packagetype_id') ? $input->get('packagetype_id') : 0;

        $cart->tour_id = $input->get('tour_id') ? $input->get('tour_id') : 0;

        $cart->packagerate_id = $input->get('packagerate_id') ? $input->get('packagerate_id') : 0;
//        echo "<pre>";
//        print_r($input->getArray($_POST));
//        exit();
        $cart->stype = $input->get('stype') ? $input->get('stype') : 0;
        $db = JFactory::getDbo();
        AImporter::model('tour');
        $module_tour = new BookProModelTour();
        $module_tour->setId($cart->tour_id ? $cart->tour_id : 0);
        $tour = $module_tour->getObject();
        $tour->days = $tour->days ? $tour->days : 3;
        $days = round($tour->days);
        $cart->checkout_date = JFactory::getDate($cart->checkin_date)->add(new DateInterval('P' . $days . 'D'));
        $cart->checkout_date = $cart->checkout_date->format('d-m-Y');

        $cart->saveToSession();
        $this->setRedirect('index.php?option=com_bookpro&view=tourbook');
    }

    function ajax_showfrom_childrenacommodation() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');


        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;


        $cart->saveToSession();
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'childrenacommodation');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '',
            'contents' => $contents
        );
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    function ajax_showfrom_airpost_transfer() {

        $view = &$this->getView('tourbook', 'html', 'BookProView');


        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__bookpro_packagerate AS packagerate');
        $query->where('packagerate');

        $a_airport_transfer = $input->get('airport_transfer') ? $input->get('airport_transfer') : 'post_airport_transfer';
        $b_airport_transfer = array(
            'post_airport_transfer' => 'post',
            'pre_airport_transfer' => 'pre'
        );
        $find_airport_transfer = array('post', 'pre', 'number', 'date', 'time');
        $replace_airport_transfer = array("post_airport_transfer", "pre_airport_transfer", "flight_number", "flight_arrival_date", "flight_arrival_time");



        $airport_transfer = $input->get($b_airport_transfer[$a_airport_transfer], array(), 'ARRAY');

        $cart->$a_airport_transfer = json_decode(str_replace($find_airport_transfer, $replace_airport_transfer, json_encode($airport_transfer)), FALSE);

        foreach ($cart->person as $key_persons => $persons) {
            foreach ($persons as $key_person => $person) {
                $b_airport_transfer = $cart->person->{$key_persons}[$key_person]->$a_airport_transfer;
                unset($cart->person->{$key_persons}[$key_person]->$a_airport_transfer);
                if (is_object($b_airport_transfer)) {
                    $cart->person->{$key_persons}[$key_person]->total-=$b_airport_transfer->price;
                }
            }
        }


        foreach ($cart->$a_airport_transfer as $key_airport_transfer => $v_airport_transfer) {
            if ($v_airport_transfer->sec_person_id == '') {
                unset($cart->$a_airport_transfer->$key_airport_transfer);
            }
        }
        $db = JFactory::getDbo();
        $db_array_airport_transfer = array(
            'post_airport_transfer' => 'pretransfer',
            'pre_airport_transfer' => 'posttransfer'
        );
        $total = 0;
        foreach ($cart->$a_airport_transfer as $v_airport_transfer) {
            $sec_person_id = explode(':', $v_airport_transfer->sec_person_id);
            if (count($sec_person_id) == 2) {
                if ($v_airport_transfer->flight_arrival_date != '') {
                    $flight_arrival_date = JFactory::getDate($v_airport_transfer->flight_arrival_date)->toSql();
                    $query = $db->getQuery(true);
                    $query->select('*');
                    $query->from('#__bookpro_packagerate AS packagerate');
                    $query->where('packagerate.date=' . $db->quote($flight_arrival_date));
                    $query->where('packagerate.tourpackage_id=' . $cart->package_id);
                    $db->setQuery($query);
                    $object_airport_transfer = $db->loadObject();
                    $v_airport_transfer->price = $object_airport_transfer->{$db_array_airport_transfer[$a_airport_transfer]};
                } else {
                    $v_airport_transfer->price = 40;
                }
                $v_airport_transfer->price = $v_airport_transfer->price ? $v_airport_transfer->price : 40;
                $total+=$v_airport_transfer->price;
                $cart->person->{$sec_person_id[0]}[$sec_person_id[1]]->$a_airport_transfer = $v_airport_transfer;
                $cart->person->{$sec_person_id[0]}[$sec_person_id[1]]->total+=$v_airport_transfer->price;
            }
        }
        $cart->$a_airport_transfer->total = $total;

        $array_airport_transfer = array(
            'post_airport_transfer' => 'posttriptransferprice',
            'pre_airport_transfer' => 'pretriptransferprice'
        );
        $cart->saveToSession();

        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', $array_airport_transfer[$a_airport_transfer]);
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array = array();
        $respone_array[] = array(
            'key' => '.triptransfer.' . $a_airport_transfer
            , 'contents' => $contents
        );
        $this->caculator_total_for_all();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    function ajax_check_coupon() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;

        $code_discount = $input->getString('code');
        $key_sec_person = $input->getString('key_sec_person');
        $key_sec_person = explode(':', $key_sec_person);
        $person = $cart->person->{$key_sec_person[0]}[$key_sec_person[1]];
        $total_discount = 0;

        $persons = $cart->person;
        AImporter::model('coupon');
        $couponModel = new BookProModelCoupon();
        $coupon = $couponModel->getObjectByCode($code_discount);
        $check = true;
        if ($coupon) {

            $list_coupon = array();
            foreach ($persons as $key_listperson => $listperson) {
                foreach ($listperson as $a_person) {
                    if (is_array($a_person->coupons)) {
                        $list_coupon = array_merge($list_coupon, $a_person->coupons);
                    }
                }
            }

            if (in_array($coupon->id, $list_coupon)) {
                $check = false;
            } elseif ((int) $coupon->total == 0) {
                $check = false;
            } else {
                if ($coupon->subtract_type == 1) {
                    $discount = ($person->total * $coupon->amount) / 100;
                    $total_discount = $discount;
                } else {
                    $total_discount = $coupon->amount;
                }
            }
        }
        if ($check == true) {

            $cart->person->{$key_sec_person[0]}[$key_sec_person[1]]->coupons[] = $coupon->id;
            $cart->person->{$key_sec_person[0]}[$key_sec_person[1]]->total_discount +=$total_discount;
            $cart->saveToSession();
        }
        $respone_array = array();
        $respone_array[] = array(
            'key' => '.enter_promo .total_discount'
            , 'contents' => CurrencyHelper::formatprice($total)
        );
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();
        $this->caculator_total_for_all();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    function caculator_total_for_all() {
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $total = 0;
        $total_discount = 0;
        $person = $cart->person;
        $a_key_persons = array(
            'adult' => 'adult',
            'teenner' => 'teenner',
            'children' => 'children'
        );

        foreach ($person as $key_person => $a_person) {

            if (!in_array($key_person, $a_key_persons, true)) {
                continue;
            }
            for ($i = 0; $i < count($a_person); $i++) {
                $passenger = $a_person[$i];
                $total_discount += $passenger->total_discount;
                $total += $passenger->total;
            }
        }
        $cart->total = $total;
        $cart->total_discount = $total_discount;

        $cart->saveToSession();
    }

    function getajax_form_totaltripprice() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        echo $contents;
        exit();
    }

    function ajax_showfrom_additionnaltrip() {

        $view = &$this->getView('tourbook', 'html', 'BookProView');


        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;

        $additionnaltrip = $input->get('additionnaltrip', array(), 'ARRAY');

        $cart->additionnaltrip = json_decode(json_encode($additionnaltrip), FALSE);

        //unset old data
        foreach ($cart->person as $key_persons => $persons) {
            foreach ($persons as $key_person => $person) {
                $cart->person->{$key_persons}[$key_person]->total -= $person->total_additionnaltrip;
                unset($cart->person->{$key_persons}[$key_person]->additionnaltrip_ids);
                unset($cart->person->{$key_persons}[$key_person]->total_additionnaltrip);
            }
        }

        $data_priceselect = array(
            'adult' => "price",
            'children' => "child_price",
            'teenner' => "price"
        );
        AImporter::model('addons');
        $modeltouraddone = new BookProModelAddons();
        $list_addone = $modeltouraddone->getItems();
        $pivot_list_addone = JArrayHelper::pivot($list_addone, 'id');
        $total = 0;
        foreach ($cart->additionnaltrip as $additionnaltrip_id => $list_sec_person_ids) {

            foreach ($list_sec_person_ids->sec_person_ids as $key => $sec_person_id) {
                $sec_person_id = explode(':', $sec_person_id);
                if (count($sec_person_id) == 2) {

                    $object_addone = new stdClass();
                    $object_addone->addon_id = $additionnaltrip_id;
                    $object_addone->price = $pivot_list_addone[$additionnaltrip_id]->$data_priceselect[$sec_person_id[0]];
                    $total+=$object_addone->price;
                    $cart->person->{$sec_person_id[0]}[$sec_person_id[1]]->additionnaltrip_ids[] = $object_addone;
                    $cart->person->{$sec_person_id[0]}[$sec_person_id[1]]->total_additionnaltrip += $object_addone->price;
                    $cart->person->{$sec_person_id[0]}[$sec_person_id[1]]->total+=$object_addone->price;
                }
            }
        }


        $cart->additionnaltrip->total = $total;

        $cart->saveToSession();
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'additionnaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.additionnaltripprice'
            , 'contents' => $contents
        );
        $this->caculator_total_for_all();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);

        exit();
    }

    function ajax_showfrom_roomselected() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');


        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;
        $setroom = $input->get('setroom', array(), 'ARRAY');
        $is_change_passenger = $input->get('is_change_passenger');
        if (!$is_change_passenger) {
            $person = $cart->person;
            $a_key_persons = array(
                'adult' => 'adult',
                'teenner' => 'teenner',
                'children' => 'children'
            );
            foreach ($person as $key_person => $a_person) {
                if (!in_array($key_person, $a_key_persons))
                    continue;
                for ($i = 0; $i < count($a_person); $i++) {
                    unset($cart->person->{$key_person}[$i]->setchildrenacommodation);
                }
            }
            unset($cart->setchildrenacommodation);
            $cart->saveToSession();
        }
        $cart->setroom = json_decode(json_encode($setroom), FALSE);
        foreach ($cart->setroom as $key_setroom => $setroom) {
            $roomtype = json_decode($setroom->roomtype);

            foreach ($setroom->person_sec_id as $person_sec_id) {
                $person_sec_id = explode(':', $person_sec_id);
                if (count($person_sec_id) == 2) {
                    $aroom = new stdClass();
                    $aroom->roomtype_id = $roomtype->id;
                    $aroom->group_room = $key_setroom;
                    $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->roombooking = $aroom;
                }
            }
        }
        $cart->saveToSession();
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'roomselected');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.roomselected'
            , 'contents' => $contents
        );




        ob_start();

        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'childrenacommodation');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.frontTourForm.children_acommodation .content',
            'contents' => $contents
        );





        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    function ajax_showfromtrip_acommodaton() {

        $view = &$this->getView('tourbook', 'html', 'BookProView');

        $db = JFactory::getDbo();
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();

        $app = JFactory::getApplication();
        $input = $app->input;
        $a_trip_acommodaton = $input->get('trip_acommodaton') ? $input->get('trip_acommodaton') : 'post_trip_acommodaton';
        $b_trip_acommodaton = array(
            'post_trip_acommodaton' => 'post',
            'pre_trip_acommodaton' => 'pre'
        );

        $trip_acommodaton = $input->get($b_trip_acommodaton[$a_trip_acommodaton], array(), 'ARRAY');
        $find_trip_acommodaton = array('post', 'pre', 'rtid', 'trip_ac', 'ids');
        $replace_trip_acommodaton = array("post_trip_acommodaton", "pre_trip_acommodaton", "roomtype_id", "trip_acommodaton", "person_sec_ids");

        $cart->$a_trip_acommodaton = json_decode(str_replace($find_trip_acommodaton, $replace_trip_acommodaton, json_encode($trip_acommodaton)), FALSE);
        //clear old data
        foreach ($cart->person as $key_persons => $persons) {
            foreach ($persons as $key_person => $person) {
                $abc_trip_acommodaton = $cart->person->{$key_persons}[$key_person]->$a_trip_acommodaton;
                unset($cart->person->{$key_persons}[$key_person]->$a_trip_acommodaton);
                if (is_object($abc_trip_acommodaton)) {
                    $cart->person->{$key_persons}[$key_person]->total-=$abc_trip_acommodaton->price;
                }
            }
        }
        foreach ($cart->$a_trip_acommodaton as $key_c_trip_acommodaton => $c_trip_acommodaton) {

            $a_total = 0;

            $checkin = JFactory::getDate($c_trip_acommodaton->checkin)->toSql();
            $checkout = JFactory::getDate($c_trip_acommodaton->checkout)->toSql();

            $interval = JFactory::getDate($c_trip_acommodaton->checkin)->diff(JFactory::getDate($c_trip_acommodaton->checkout));


            $query = $db->getQuery(TRUE);
            $query->select('roomprice.*,CONCAT(roomprice.roomtype_id,DATE_FORMAT(date, "/%d/%m/%Y")) AS roomtype_date ');
            $query->from('#__bookpro_roomprice AS roomprice');
            $query->where('roomprice.tourpackage_id=' . ($cart->package_id ? $cart->package_id : 0));
            $query->where('date between "' . $checkin . '" and "' . $checkout . '"');
            $db->setQuery($query);
            //echo $db->replacePrefix($query);
            $list_room = $db->loadObjectList('roomtype_date');

            foreach ($list_room as $key_room_price => $room_price) {
                if ($room_price->roomtype_id == 0) {
                    $roomtype_id = rand(1, 4);
                    $list_room[$key_room_price]->roomtype_id = $roomtype_id;
                    $query = $db->getQuery(TRUE);
                    $query->update($db->qn('#__bookpro_roomprice'))
                            ->set('roomtype_id = ' . $roomtype_id)
                            ->where('id=' . $room_price->id);
                    $db->setQuery($query);
                    $db->query();
                }
            }



            $checkin = JFactory::getDate($c_trip_acommodaton->checkin);
            $checkout = JFactory::getDate($c_trip_acommodaton->checkout);
            $grouproom = 0;
            foreach ($c_trip_acommodaton->trip_acommodaton as $key_trip_acommodaton => $trip_acommodaton) {

                if (trim($trip_acommodaton->roomtype_id) != 0) {
                    $roomtype_id = $trip_acommodaton->roomtype_id;
                    $roomtype_id = explode(':', $roomtype_id);
                    $max_person = $roomtype_id[1];
                    $roomtype_id = $roomtype_id[0];

                    foreach ($trip_acommodaton->setroom as $key_setroom => $setroom) {
                        foreach ($setroom->person_sec_ids as $person_sec_id) {
                            $person_sec_id = explode(':', $person_sec_id);
                            if (count($person_sec_id) == 2) {

                                $b_trip_acommodaton = new stdClass();
                                $b_trip_acommodaton->roomtype = $roomtype_id;
                                $b_trip_acommodaton->checkin = $checkin->format('d-m-Y');
                                $b_trip_acommodaton->checkout = $checkout->format('d-m-Y');
                                $b_trip_acommodaton->interval = $interval->days;

                                $b_trip_acommodaton->group_room = $grouproom;
                                $total = 0;
                                $trip_acommodaton_detail = array();
                                $a_checkin = clone $checkin;
                                $a_checkout = clone $checkout;
                                while ($a_checkin < $a_checkout) {
                                    $std_trip_acommodaton_detail = new stdClass();
                                    $std_trip_acommodaton_detail->date = $a_checkin->format('d-m-Y');

                                    $key_roomtype_date = $roomtype_id . '/' . $a_checkin->format('d/m/Y');
                                    if (is_object($obj_roomtype_date = $list_room[$key_roomtype_date])) {
                                        $price = $obj_roomtype_date->price / $max_person;
                                        $total+=$price;
                                        $std_trip_zacommodaton_detail->price = $price;
                                    } else {
                                        $total+=40 / $max_person;
                                        $std_trip_acommodaton_detail->price = 40 / $max_person;
                                    }
                                    $trip_acommodaton_detail[] = $std_trip_acommodaton_detail;
                                    $a_checkin->modify('+1 day');

                                    //neu khong co thi tinh mac dinh la 40
                                }
                                $b_trip_acommodaton->trip_acommodaton_detail = $trip_acommodaton_detail;
                                $b_trip_acommodaton->price = $total;
                                $a_total+=$total;
                                $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->$a_trip_acommodaton = $b_trip_acommodaton;
                                $cart->person->{$person_sec_id[0]}[$person_sec_id[1]]->total+=$total;
                            }
                        }
                        $grouproom++;
                    }
                } else {

                    unset($cart->{$a_trip_acommodaton}[$key_c_trip_acommodaton]->trip_acommodaton[$key_trip_acommodaton]);
                }
            }
            $cart->{$a_trip_acommodaton}[$key_c_trip_acommodaton]->total = $a_total;
        }

        $array_trip_acommodaton = array(
            'post_trip_acommodaton' => 'posttripprice',
            'pre_trip_acommodaton' => 'pretripprice'
        );
        $cart->saveToSession();
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', $array_trip_acommodaton[$a_trip_acommodaton]);
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.tripprice.' . $a_trip_acommodaton
            , 'contents' => $contents
        );
        $this->caculator_total_for_all();
        ob_start();

        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'totaltripprice');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.totaltripprice'
            , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    function showFormOption() {

        AImporter::helper('bookpro');
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;

        $cart->needasignchildrenforspecialroom = null;
        $cart->additionnaltrip = null;
        $cart->post_trip_acommodaton = null;
        $cart->pre_trip_acommodaton = null;
        $cart->post_airport_transfer = null;
        $cart->pre_airport_transfer = null;
        $cart->setroom = null;
        $cart->person = null;
        $cart->setchildrenacommodation = null;
        //$cart->clear();
        $app = JFactory::getApplication();
        $input = $app->input;
        $person = $input->get('person', array(), 'ARRAY');
        $cart->person = json_decode(json_encode($person), FALSE);
        $total_adult = $input->get('adult');
        $packagerate_id = $input->get('packagerate_id') ? $input->get('packagerate_id') : 0;
        $cart->packagerate_id = $packagerate_id ? $packagerate_id : $cart->packagerate_id;

        $cart->obj_id = $cart->packagerate_id;
        $total_teenner = $input->get('teenner');
        $total_children = $input->get('children');
        $person = $cart->person;
        if ($total_adult == 0)
            unset($cart->person->adult[0]);
        if ($total_teenner == 0)
            unset($cart->person->teenner[0]);
        if ($total_children == 0)
            unset($cart->person->children[0]);
        $total_person = $total_adult + $total_teenner;
        $cart->person->total_person = $total_person;
        $checkin_date = JFactory::getDate($cart->checkin_date);
        $checkin_date = JFactory::getDate($checkin_date->format('Y-m-d'));
        $cart->saveToSession();
        $this->resetpriceforthistour($cart);

        $this->caculator_total_for_all();
        $nonedaytrip = array(
            "nonedaytripprivate",
            "nonedaytripshared"
        );
        $daytrip = array(
            "private", "shared"
        );
        if (in_array($cart->stype, $daytrip)) {
            $this->setRedirect('index.php?option=com_bookpro&view=tourbook&layout=passenger&tpl=default&Itemid=' . $input->get('Itemid'));
            return;
        }
        $this->setRedirect('index.php?option=com_bookpro&view=tourbook&layout=option&tpl=default&Itemid=' . $input->get('Itemid'));
    }

    function resetpriceforthistour($cart, $needasign_children_for_special_room = false) {
        AImporter::helper('bookpro');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $table_packagerate = $cart->stype != '' && $cart->stype == 'shared' ? '#__bookpro_packageratedaytripjoingroup' : '#__bookpro_packagerate';
        $query->select('packagerate.*');
        $query->from($table_packagerate . ' as packagerate');
        $query->where('packagerate.id=' . $cart->obj_id);

        $db->setQuery($query);

        $date_package_price = $db->loadObject();

//        print_r($db->replacePrefix($query));
//        exit();
        foreach ($cart->person as $key_persons => $persons) {
            foreach ($persons as $key_person => $person) {
                $price = 0;
                if ($key_persons == 'adult' || $key_persons == 'teenner') {
                    $array_persontype = array('adult' => 'adult', 'teenner' => 'teen');
                    if ($date_package_price->adult_promo != 0) {
                        $array_persontype['adult'] = 'adult_promo';
                    }
                    if ($date_package_price->teen_promo != 0) {
                        $array_persontype['teenner'] = 'teen_promo';
                    }
                    $price = $date_package_price->{$array_persontype[$key_persons]};
                } else {
                    $year_old = BookProHelper::getyearold(JFactory::getDate($person->birthday)->format('Y/m/d'));
                    if ($year_old < 2) {
                        $price = $date_package_price->child3;
                    } else if ($year_old >= 2 && $year_old <= 5) {
                        $price = $date_package_price->child2;
                    } else if ($year_old >= 6 && $year_old <= 11) {
                        $price = $date_package_price->child1;
                    }

                    if ($needasign_children_for_special_room) {

                        $price = $date_package_price->teen;
                        if ($date_package_price->teen_promo != 0) {
                            $price = $date_package_price->teen_promo;
                        }
                    }
                }
                $oldprice = $cart->person->{$key_persons}[$key_person]->priceroomselect;
                $cart->person->{$key_persons}[$key_person]->priceroomselect = $price;
                $cart->person->{$key_persons}[$key_person]->total-=$oldprice;
                $cart->person->{$key_persons}[$key_person]->total+=$price;
            }
        }

        $cart->saveToSession();
    }

    function ajax_showfrom_checkinandcheckout() {
        $view = &$this->getView('tourbook', 'html', 'BookProView');


        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $app = JFactory::getApplication();
        $input = $app->input;
        $cart->package_id = $input->get('package_id') ? $input->get('package_id') : 0;

        $cart->checkin_date = JFactory::getDate($input->get('checkin'))->format('d-m-Y');

        AImporter::model('tour');
        $module_tour = new BookProModelTour();

        $module_tour->setId($cart->tour_id ? $cart->tour_id : 0);
        $tour = $module_tour->getObject();
        $tour->days = $tour->days ? $tour->days : 3;
        $tour->days = $tour->days - 3;
        $days = $tour->days;
        $nonedaytrip = array(
            "nonedaytripprivate",
            "nonedaytripshared"
        );
        $daytrip = array(
            "private", "shared"
        );
        if (in_array($cart->stype, $daytrip)) {
            $days = 0;
        }

        $cart->checkout_date = JFactory::getDate($cart->checkin_date)->add(new DateInterval('P' . $days . 'D'));
        $cart->checkout_date = $cart->checkout_date->format('d-m-Y');


        $cart->saveToSession();
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'option');
        JRequest::setVar('tpl', 'checkinandcheckout');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.checkinandcheckout',
            'contents' => $contents
        );
        $packagetypes = JArrayHelper::pivot($view->getPackagetypes($cart), 'id');


        $modeltourpackage = new BookProModelTourPackage();
        $modeltourpackage->setId(($cart->package_id ? $cart->package_id : 0));
        $tourpackage = &$modeltourpackage->getObject();


        $respone_array[] = array(
            'key' => '.tourpackagetype_header',
            'contents' => $packagetypes[$cart->packagetype_id]->title . '- ' . $tourpackage->min_person . ' GUESTS ROM 12 YEARS UP'
        );

        echo json_encode($respone_array);
        exit();
    }

    function confirmbooking() {

        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
		$config=AFactory::getConfig();
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $input = $app->input;

        $this->setRedirect('index.php?option=com_bookpro&view=tourbook&layout=payment&tpl=complete&Itemid=' . $input->get('Itemid'));
        if (!class_exists('BookProModelPassenger')) {
            AImporter::model('passenger');
        }
        if (!class_exists('BookProModelOrderInfo')) {
            AImporter::model('orderinfo');
        }
        if (!class_exists('BookProModelOrder')) {
            AImporter::model('order');
        }
        if (!class_exists('BookProModelCustomer')) {
            AImporter::model('customer');
        }
        $app = &JFactory::getApplication();
        $cmodel = new BookProModelCustomer();
        $pModel = new BookProModelPassenger();
        $orderModel = new BookProModelOrder();
        $orderModelInfo = new BookProModelOrderInfo();

        AImporter::model('addons', 'customer');
        $sum = 0;
        $modeltouraddone = new BookProModelAddons();
        $list_addone = $modeltouraddone->getItems();
        $listadditionnaltrip = JArrayHelper::pivot($list_addone, 'id');


        if (!class_exists('BookProModelCustomer')) {
        	AImporter::model('customer');
        }
        $model_customer=new BookProModelCustomer();
    	if (! $cart->leader) {
			$cart->leader = 'adult:0';
		}
		//$person_leader = explode ( ':', $cart->leader );
		//$person_leader = $cart->person->{$person_leader [0]} [$person_leader [1]];
		// check exists user system by email

		$user_system = JFactory::getUser();
		if (! $user_system->id) {
			$config=AFactory::getConfig();
			$data ['id']=0;
			$data ['email'] = $input->get('email','','string');
			$data ['username'] = $input->get('newusername','','string');
			$data ['name'] = $input->get('newusername','','string');
			$data ['password'] = $input->get('newuserpasswd','','string');
			$data ['block']=0;
			$data ['groups']=array($config->customersUsergroup);
			$user_system = $model_customer->createUserSystem ( $data );
			$model_customer->autoLoginByUserIdSystem ( $user_system->id );

		}
		$customer = $model_customer->getCustomerByUserIdSystem ( $user_system->id );

		if (! $customer->id) {
			$extend_data=array();
			$extend_data['firstname']=$input->get('newusername','','string');
			$extend_data['lastname']=$input->get('newusername','','string');


			$customer = $model_customer->createCustomerByUserIdSystem ( $user_system->id ,$extend_data);
		}


        $cid = $customer->id;
        AImporter::model('application');
        $cart->adult = count($cart->person->adult);
        $cart->tenner = count($cart->person->tenner);
        $cart->children = count($cart->person->children);

        $order = array(
            'id' => 0,
            'type' => 'TOUR',
            'user_id' => $cid,
            'total' => $cart->total,
            'subtotal' => $cart->sum,
            'pay_method' => 'UNDEFINED',
            'pay_status' => 'PENDING',
            'notes' => $cart->notes,
            'tax' => $cart->tax,
            'order_status' => 'PENDING',
            'service_fee' => $cart->service_fee,
            'tour_type' => $cart->stype ? $cart->stype : ""
        );
        $orderid = $orderModel->store($order);

        $info = array(
            'adult' => $cart->adult,
            'tenner' => $cart->tenner,
            'child' => $cart->children,
            'obj_id' => $cart->obj_id,
            'price' => $cart->sum,
            'start' => JFactory::getDate($cart->checkin_date)->toSql(),
            'end' => JFactory::getDate($cart->checkout_date)->toSql(),
            'order_id' => $orderid
        );
        $orderModelInfo->store($info);


        $a_key_persons = array(
            'adult' => 'adult',
            'teenner' => 'teenner',
            'children' => "children"
        );

        foreach ($cart->person as $key_persons => $listpersons) {
            if (!in_array($key_persons, $a_key_persons))
                continue;

            for ($i = 0; $i < count($listpersons); $i++) {
                $person = $listpersons[$i];

                $passenger = array(
                    'id'=>0,
                    'leader' => $person->leader,
                    'firstname' => $person->firstname,
                	'gender' => $person->gender,
                    'lastname' => $person->lastname,
                    'birthday' => JFactory::getDate($person->birthday)->toSql(),
                    'email' => $person->email,
                    'homephone' => $person->homephone,
                    'mobile' => $person->mobile,
                    'address' => $person->address,
                    'suburb' => $person->suburb,
                    'privince' => $person->privince,
                    'code_zip' => $person->code_zip,
                    'passport' => $person->passport,
                    'passport_issue' => JFactory::getDate($person->passport_issue)->toSql(),
                    'passport_expiry' => JFactory::getDate($person->passport_expiry)->toSql(),
                    'emergency_name' => $person->emergency_name,
                    'emergency_mobile' => $person->emergency_mobile,
                    'emergency_homephone' => $person->emergency_homephone,
                    'emergency_address' => $person->emergency_address,
                    'aditional_request' => $person->textarea_aditional_request,
                    'meal_requement' => $person->textarea_meal_requement,
                    'order_id' => $orderid
                );
                foreach ($passenger as $key_passenger => $value_passenger) {
                    if (trim($value_passenger) == "") {
                        unset($passenger[$key_passenger]);
                    }
                }

                $pModel = new BookProModelPassenger();
                $passenger_id = $pModel->store($passenger);
				if(!$passenger_id)
				{
					JError::raiseError(500, $pModel->getError());
					return false;
				}
                $array_insert = array();
                if (count($person->additionnaltrip_ids)) {
                    foreach ($person->additionnaltrip_ids as $additionnaltrip_id) {

                        $array_insert[] = array(
                            "addone_id" => $additionnaltrip_id->addon_id,
                            "order_id" => $orderid,
                            "passenger_id" => $passenger_id,
                            "price" => $additionnaltrip_id->price
                        );
                    }
                    if (count($array_insert)) {
                        $query = $db->getQuery(true);
                        $query->insert('#__bookpro_order_addonpassenger');
                        $query->columns(implode(',', array_keys($array_insert[0])));
                        foreach ($array_insert as $insert) {
                            $query->values(implode(',', array_values($insert)));
                        }
                        $db->setQuery($query);

                        $db->query();
                    }
                }

                if ($person->roombooking) {

                    $query = $db->getQuery(true);
                    $query->insert('#__bookpro_order_roomtypepassenger');
                    $array_insert = array(
                        "type" => $db->quote('0'),
                        "roomtype_id" => $person->roombooking->roomtype_id,
                        "passenger_id" => $passenger_id,
                        "checkin" => $db->quote(JFactory::getDate($cart->checkin_date)->toSql()),
                        "checkout" => $db->quote(JFactory::getDate($cart->checkout_date)->toSql()),
                        "grouproom" => $person->roombooking->group_room,
                        "order_id" => $orderid,
                        "extrabed" => $person->setchildrenacommodation->needbed == 1 ? 1 : 0
                    );

                    $query->columns(implode(',', array_keys($array_insert)));
                    $query->values(implode(',', array_values($array_insert)));

                    $db->setQuery($query);

                    $db->query();
                }

                $query = $db->getQuery(true);
                $query->insert('#__bookpro_order_tourpassenger');
                $array_insert = array(
                    "packagerate_id" => $cart->obj_id,
                    "tour_type" => $db->quote($cart->stype ? $cart->stype : ""),
                    "passenger_id" => $passenger_id,
                    "order_id" => $orderid,
                    "price" => $person->priceroomselect,
                );
                $query->columns(implode(',', array_keys($array_insert)));
                $query->values(implode(',', array_values($array_insert)));
                $db->setQuery($query);
                $db->query();




                $array_trip_acommodaton = array(
                    1 => 'post_trip_acommodaton',
                    2 => 'pre_trip_acommodaton'
                );

                $array_insert = array();
                foreach ($array_trip_acommodaton as $trip_acommodaton) {

                    if ($person->$trip_acommodaton) {

                        $roomtype = explode(':', $person->$trip_acommodaton->roomtype);
                        $array_insert[] = array(
                            "type" => $db->quote($trip_acommodaton),
                            "roomtype_id" => $roomtype[0],
                            "passenger_id" => $passenger_id,
                            "order_id" => $orderid,
                            "checkin" => $db->quote(JFactory::getDate($person->$trip_acommodaton->checkin)->toSql()),
                            "checkout" => $db->quote(JFactory::getDate($person->$trip_acommodaton->checkout)->toSql()),
                            "grouproom" => $person->$trip_acommodaton->group_room,
                            "price" => $person->$trip_acommodaton->price,
                            "order_id" => $orderid
                        );
                    }
                }

                if (count($array_insert)) {

                    foreach ($array_insert as $insert) {
                        $query = $db->getQuery(true);
                        $query->insert('#__bookpro_order_roomtypepassenger');
                        $query->columns(implode(',', array_keys($insert)));
                        $query->values(implode(',', array_values($insert)));
                        $db->setQuery($query);
                        $db->query();
                    }
                }

                $array_airport_transfer = array(
                    1 => 'post_airport_transfer',
                    2 => 'pre_airport_transfer'
                );

                $array_insert = array();
                foreach ($array_airport_transfer as $airport_transfer) {

                    if ($person->$airport_transfer) {
                        $roomtype = explode(':', $person->$airport_transfer->roomtype);
                        $array_insert[] = array(
                            "type" => $db->quote($airport_transfer),
                            "passenger_id" => $passenger_id,
                            "order_id" => $orderid,
                            "flightnumber" => $db->quote($person->$airport_transfer->flight_number),
                            "arrival_date_time" => $db->quote(JFactory::getDate($person->$airport_transfer->checkin)->toSql()),
                            "price" => $person->$airport_transfer->price ? $person->$airport_transfer->price : 0
                        );
                    }
                }
                if (count($array_insert)) {

                    foreach ($array_insert as $insert) {
                        $query = $db->getQuery(true);
                        $query->insert('#__bookpro_order_transferpassenger');
                        $query->columns(implode(',', array_keys($insert)));
                        $query->values(implode(',', array_values($insert)));
                        $db->setQuery($query);

                        $db->query();
                    }
                }
            }
        }

        //$cart->clear();
        $this->setRedirect('index.php?option=com_bookpro&view=formpayment&order_id=' . $orderid.'&'. JSession::getFormToken().'=1');
    }

    function show_form_input_detail_passenger() {
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();

        $app = JFactory::getApplication();
        $input = $app->input;
//        $setroom = $input->get('setroom', array(), 'ARRAY');
//        $cart->setroom = json_decode(json_encode($setroom), FALSE);
//
//        $setchildrenacommodation = $input->get('setchildrenacommodation', array(), 'ARRAY');
//        $cart->setchildrenacommodation = json_decode(json_encode($setchildrenacommodation), FALSE);
//
//        $pre_trip_acommodaton = $input->get('pre_trip_acommodaton', array(), 'ARRAY');
//        $cart->pre_trip_acommodaton = json_decode(json_encode($pre_trip_acommodaton), FALSE);
//        foreach ($cart->pre_trip_acommodaton->trip_acommodaton as $key_trip_acommodaton => $trip_acommodaton) {
//            if (trim($trip_acommodaton->roomtype_id) == 0) {
//                unset($cart->pre_trip_acommodaton->trip_acommodaton[$key_trip_acommodaton]);
//            }
//        }
//
//        $post_trip_acommodaton = $input->get('post_trip_acommodaton', array(), 'ARRAY');
//        $cart->post_trip_acommodaton = json_decode(json_encode($post_trip_acommodaton), FALSE);
//
//        foreach ($cart->post_trip_acommodaton->trip_acommodaton as $key_trip_acommodaton => $trip_acommodaton) {
//            if (trim($trip_acommodaton->roomtype_id) == 0) {
//                unset($cart->post_trip_acommodaton->trip_acommodaton[$key_trip_acommodaton]);
//            }
//        }
//
//        $pre_airport_transfer = $input->get('pre_airport_transfer', array(), 'ARRAY');
//        $cart->pre_airport_transfer = json_decode(json_encode($pre_airport_transfer), FALSE);
//
//        $post_airport_transfer = $input->get('post_airport_transfer', array(), 'ARRAY');
//        $cart->post_airport_transfer = json_decode(json_encode($post_airport_transfer), FALSE);
//
//        $additionnaltrip = $input->get('additionnaltrip', array(), 'ARRAY');
//        $cart->additionnaltrip = json_decode(json_encode($additionnaltrip), FALSE);
//
//
//        $cart->saveToSession();

        $this->setRedirect('index.php?option=com_bookpro&view=tourbook&layout=passenger&tpl=default&Itemid=' . $input->get('Itemid'));
    }

    function show_form_payment() {

        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();


        $app = JFactory::getApplication();
        $input = $app->input;
        $persons = $input->get('person', array(), 'ARRAY');

        $persons = json_decode(json_encode($persons), false);

        foreach ($persons as $key_persons => $listperson) {
            $i = 0;
            if (count($listperson)) {
                foreach ($listperson as $person) {
                    foreach ($person as $key_person => $value) {
                        $cart->person->{$key_persons}[$i]->{$key_person} = $value;
                        if($value==1&&$key_person=='leader')
                        {
                        	$cart->leader=$key_persons.':'.$i;
                        }
                    }
                    $i++;
                }
            }
        }


//        $listroomtype = JArrayHelper::pivot($this->listroomtype, 'id');
//        $person = $cart->person;
//
//        foreach ($cart->additionnaltrip as $additionnaltrip) {
//
//            foreach ($additionnaltrip->sec_person_ids as $sec_person_id) {
//                $sec_person_id = explode(':', $sec_person_id);
//                if (count($person_sec_id) == 2) {
//                    print_r($person_sec_id);
//                    //print_r($cart->person->{$sec_person_id[0]}[$sec_person_id[1]]);
//                    //$cart->person->{$sec_person_id[0]}[$sec_person_id[1]]->additionnaltrip_ids[] = 3;
//                }
//            }
//        }
//
//        echo "<pre>";
//
//        print_r($cart->person);
//         exit();
		AImporter::model('application');
        $applicationModel = new BookProModelApplication();

        $aaa = $applicationModel->getObjectByCode('TOUR');
        $sum=0;
        $cart->sum = 0;
        $cart->tax = $sum * $aaa->vat / 100;
        $cart->service_fee = $aaa->service_fee * $sum / 100;
        $cart->total = ($cart->total + $cart->tax + $cart->service_fee)-$cart->total_discount;
        $groups=JUserHelper::getUserGroups($user_system->id);

        if(in_array($config->agentUsergroup, $groups))
        {
        	if (!class_exists('BookProModelTour')) {
        		AImporter::model('tour');
        	}
        	$model_tour=new BookProModelTour();
        	$model_tour->setId($cart->tour_id);
        	$tour=$model_tour->getObject();
        	$cart->total=$cart->total-($cart->total*$tour->agent_discount)/100;
        }
        $cart->saveToSession();

        $this->setRedirect('index.php?option=com_bookpro&view=tourbook&layout=payment&tpl=default&Itemid=' . $input->get('Itemid'));
    }

}
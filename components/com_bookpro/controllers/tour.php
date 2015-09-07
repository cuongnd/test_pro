<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
AImporter::helper('request', 'tour', 'html');

class BookProControllerTour extends JControllerLegacy {

    var $app;

    public function BookProControllerTour() {
        $document = JFactory::getDocument();
        if (!class_exists('BookProModelApplication')) {
            AImporter::model('application');
        }
        $appModel = new BookProModelApplication();
        $this->app = $appModel->getObjectByCode('TOUR');
        parent::__construct();
    }

    public function display($cachable = false, $urlparams = false) {


        $document = JFactory::getDocument();
        $vName = JRequest::getCmd('view', 'login');
        $user = JFactory::getUser();
        switch ($vName) {
            case 'tourbook':
                $this->book_form();
                return;
            case 'tour':
                $this->displaytour();
                return;
        }
        JRequest::setVar('view', $vName);
        parent::display();
    }

    function getajax_showform_package_price() {


        $app = JFactory::getApplication();
        $input = $app->input;
        ob_start();

        JRequest::setVar('id', $input->get('tour_id'));
       
        
        $view = &$this->getView('tour', 'html', 'BookProView');

        AImporter::model('tour');
        $model_tour = new BookProModelTour();
        $tour = $model_tour->getFullTourObject($input->get('tour_id'));
        $stype = $tour->daytrip ? 'daytrip' : $tour->stype;
        JRequest::setVar('checkin', $input->get('checkin'));
        JRequest::setVar('layout', 'default');
        $view->display('package_' . $stype . '_price');
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        echo $contents;
        exit();
    }

    function display_booking_form() {

        AImporter::model('tourpackage', 'tour');

        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        //
        $view = &$this->getView('tourbook', 'html', 'BookProView');
        $packageModel = new BookProModelTourPackage();
        $packageModel->setId($cart->package_id);
        $package = $packageModel->getObject();

        $model = new BookProModelTour();
        $model->setId($package->tour_id);
        $tour = $model->getObject();

        if ($tour->hotel_id) {
            AImporter::model('hotel');
            $hotelModel = new BookProModelHotel();
            $hotelModel->setId($tour->hotel_id);
            $hotel = $hotelModel->getObject();
            $view->assign('hotel', $hotel);
        }

        $view->assign('tour', $tour);
        $view->assign('cart', $cart);
        $view->assign('package', $package);
        $view->assign('app', $this->app);

        //$itinerarys=TourHelper::buildItinerary($id);
        //$view->assign('itineraries',$itinerarys);
        $view->display();
    }

    function book_form() {

        $input = JFactory::getApplication()->input;
        $package_id = $input->getInt('package_id');
        $depart = $input->getString('depart');
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();
        $cart->depart = $depart;
        $cart->package_id = $package_id;
        $cart->saveToSession();
        $this->check($step);
    }

    private function check($step = 1) {
        $user = JFactory::getUser();
        $config = AFactory::getConfig();
        if ($config->anonymous) {
            $this->display_booking_form();
        } else {
            if ($user->id == 0) {
                $return = base64_encode(JURI::root() . 'index.php?option=com_bookpro&controller=tour&task=display_booking_form');
                $return = JURI::root() . 'index.php?option=com_bookpro&view=login&return=' . $return;
                $this->setRedirect($return);
                return;
            } else {
                $this->display_booking_form();
            }
        }
    }

    function confirm() {
        JSession::checkToken() or jexit('Invalid Token');
        AImporter::model('tour', 'tourpackage', 'order', 'orderinfo');
        $app = JFactory::getApplication();
        $input = $app->input;

        $config = AFactory::getConfig();
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();

        $user = JFactory::getUser();

        $packageModel = new BookProModelTourPackage();
        $packageModel->setId($cart->package_id);
        $package = $packageModel->getObject();


        $model = new BookProModelTour();
        $model->setId($package->tour_id);
        $tour = $model->getObject();

        $roomtype = $input->get('roomtype', null, array());
        $extra_bed = $input->get('extra_bed', null, array());

        //customer
        $notes = JRequest::getVar('notes', "");
        $location = JRequest::getVar('location');
        $cart->notes = $notes;
        //

        $orderinfo = array();
        $orderinfo[] = array('obj_id' => $cart->package_id,
            'start' => $start,
            'type' => 'TOUR',
            'location' => $location);
        //process if tour has room selected
        if ($roomtype) {
            for ($i = 0; $i < count($roomtype); $i++) {
                $orderinfo[] = array('obj_id' => $roomtype[$i],
                    'start' => $start,
                    'infant' => $extra_bed[$i],
                    'type' => 'ROOMTYPE');
            }
        }
        $post = $input->getArray($_POST);
        $user = JFactory::getUser();
        if ($user->guest) {
            JTable::addIncludePath(JPATH_COMPONENT_FRONT_END . '/tables');
            $customer = JTable::getInstance('customer', 'table');
            $customer->bind($post);
            if (!$customer->store()) {
                $app->enqueueMessage('Can not save customer', 'error');
            } else {
                $cid = $customer->id;
            }
        } else {
            AImporter::model('customer');
            $cmodel = new BookProModelCustomer();
            $cmodel->setIdByUserId();
            $cold = $cmodel->getObject();
            $post['id'] = $cold->id;
            $cid = $cmodel->store($post);
            if ($err = $cmodel->getError()) {
                $app->enqueueMessage($err, 'error');
            }
        }
        $orderModel = new BookProModelOrder();
        $order = array('id' => $cart->order_id,
            'type' => 'TOUR',
            'user_id' => $cid,
            'total' => $cart->total,
            'subtotal' => $cart->sum,
            'pay_method' => '',
            'order_status' => OrderStatus::$NEW->getValue(),
            'pay_status' => 'PENDING',
            'notes' => $cart->notes,
            'tax' => $cart->tax,
            'discount' => 0,
            'service_fee' => $cart->service_fee
        );

        $order_id = $orderModel->batchSave($order, $orderinfo);

        if (!$order_id) {
            $app->redirect(JURI::base(), 'Save order error', 'error');
            return;
        }
        //process if booking need the passenger information
        if ($config->passengerStatus) {
            AImporter::model('passenger');
            $pModel = new BookProModelPassenger();
            $pFirstname = $input->get('pFirstname', null, array());
            $pGender = $input->get('pGender', null, array());
            $pMiddlename = $input->get('pMiddlename', array());
            $pGroup = $input->get('group_id', null, array());
            $pPassport = $input->get('pPassport', null, array());
            $pCountry = $input->get('pCountry', null, array());
            $pBirthday = $input->get('pBirthday', null, array());

            $total = TourHelper::getPrice($package->price, $pGroup);

            for ($i = 0; $i < count($pFirstname); $i++) {
                $passenger = array('gender' => $pGender[$i],
                    'firstname' => $pFirstname[$i],
                    'lastname' => $pMiddlename[$i],
                    'passport' => $pPassport[$i],
                    'birthday' => JFactory::getDate($pBirthday[$i])->toSql(),
                    'group_id' => $pGroup[$i],
                    'country_id' => $pCountry[$i],
                    'order_id' => $order_id
                );
                $pModel->store($passenger);
                if ($err = $pModel->getError()) {
                    $app->enqueueMessage($err, 'error');
                    $app->redirect(JURI::base());
                    exit;
                }
            }
            //update order price
            JTable::addIncludePath(JPATH_COMPONENT_FRONT_END . '/tables');
            $order = JTable::getInstance('orders', 'table');
            $order->load($order_id);
            $order->total = $total;
            $order->store();
        }
        //end passenger
        $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&view=formpayment&order_id=' . $order_id . '&' . JSession::getFormToken() . '=1');
        return;
    }

    function searchadv() {


        $input = JFactory::getApplication()->input;
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();

        $config = JFactory::getConfig();
        AImporter::model('tours','country');
        $model = new BookProModelTours();
        $lists = array();
        $lists['keyword'] = JRequest::getString('keyword', null);
        $lists['limit'] = ARequest::getUserStateFromRequest('limit', 5, 'int');
        $lists['state'] = 1;
        $lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'ordering', 'cmd');
        $lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');

        $lists['country_id'] = $input->getInt('country_id');
        $lists['days'] = $input->getInt('days', null);
        $lists['dest_id'] = $input->getInt('dest_id', null);
        $lists['cat_id'] = $input->getInt('cat_id', null);
        $lists['private'] = $input->getString('private', '');
        $lists['activity'] = $input->getInt('activity', null);
        $lists['rank'] = $input->getInt('rank', 3);
        $depart_date = $input->getString('depart_date', null);
        $return_date = $input->getString('return_date', null);
        /*
          if (!$depart_date) {
          $depart_date=JFactory::getDate();
          $depart_date->add(new DateInterval('P1D'));
          $depart_date=$checkin->format('Y-m-d');
          }
          if (!$return_date) {
          $return_date=JFactory::getDate();
          $return_date->add(new DateInterval('P3D'));
          $return_date=$checkout->format('Y-m-d');
          }
          if($depart_date && $return_date){
          $period=$depart_date.';'.$return_date;
          $lists['period']=$period;
          }
         */
        
        $cart->filter = $lists;
        $cart->saveToSession();
		
        $table = JTable::getInstance('Country','Table');
        $table->load($input->getInt('country_id'));
        
        if (!$table->id) {
        	$country_name = JText::_('COM_BOOKPRO_SEARCH_ALL');
        }else{
        	$country_name = $table->country_name;
        }
        
		$layout = $input->getString('layout', 'list');
        $model->init($cart->filter);

        //echo $model->buildQuery();
        $data = $model->getData();
        $total = $model->getTotal();
        
        $pagination = $model->getPagination();
        $view = $this->getView('tours', 'html', 'BookProView');
        $view->assign('tours', $data);
        $view->assign('total',$total);
        $view->assign('country_name',$country_name);
        $view->assign('layout',$layout);
        
        $view->assign('pagination', $pagination);
        $view->display();
    }

    function displaytour() {
        AImporter::model('tour', 'tourpackages');
        $input = JFactory::getApplication()->input;
        $cart = &JModelLegacy::getInstance('TourCart', 'bookpro');
        $view = &$this->getView('Tour', 'html', 'BookProView');
        $cart->load();
        $cart->clear();

        $model = new BookProModelTour();
        $id = $input->getInt('id');
        $tour = $model->getFullTourObject($id);

        if ($tour->hotel_id) {
            AImporter::model('hotel');
            $hotelModel = new BookProModelHotel();
            $hotelModel->setId($tour->hotel_id);
            $hotel = $hotelModel->getObject();
            $view->assign('hotel', $hotel);
        }

        $tmodel = new BookProModelTourPackages();
        $tmodel->init(array('tour_id' => $id, 'state' => 1));
        $packages = $tmodel->getData();

        $view->assign('tour', $tour);

        $view->assign('packages', $packages);
        

        $view->display();
    }

    /**
     * ajax function for airworld.com.my
     */
    function getPriceByDate() {

        if (!class_exists('BookProModelPackagePrices')) {
            AImporter::model('packageprices');
        }
        $depart = JRequest::getVar('depart_date');
        $tour_id = JRequest::getVar('tour_id');

        $result = array();
        $priceModel = new BookProModelPackagePrices();
        $prices = $priceModel->getPriceByTourAndDate($tour_id, $depart);

        //
        $model = new BookProModelTour();
        $model->setId($tour_id);
        $tour = $model->getObject();
        //
        foreach ($prices as $price) {

            if ($price->price > 0)
                $price->type = 'adult';
            else
                $price->type = 'child';

            $result[] = $price;
        }
        $view = &$this->getView('Tour', 'html', 'BookProView');
        $view->setLayout('price');
        $view->assign('result', $result);
        $view->assign('tour', $tour);
        $view->assign('depart', $depart);
        $view->display();
    }

    function getCampareYear() {
        $jdate = JRequest::getVar('jdate');
        $date = new JDate();
        $date1 = new JDate($jdate);
        echo $date1->diff($date)->y;
        die;
    }

    function ExportTourToPDFFile() {

        $mainframe = &JFactory::getApplication();
        $this->tour_id = JRequest::getVar('tour_id');


        if (file_exists('components/com_bookpro/controllers/tcpdf/tcpdf.php') && $this->tour_id) {
            require_once ('components/com_bookpro/controllers/tcpdf/tcpdf.php');

            AImporter::model("tour", "itineraries", "airport", "hotel");
            AImporter::helper('tour');

            $modelTour = new BookproModelTour();
            $modelTour->setId($this->tour_id);
            $this->tour = $modelTour->getObject();
            $html .= '<div ><h2>' . $this->tour->title . '</h2>';
            $html .= '<table class="table">
                <thead style="background:#95a5a5!important;text-transform:uppercase; color:#fff; font-weight:normal;">
                <tr>
                <th>' . JText::_('COM_BOOKPRO_PDF_CLASS') . '</th>
                <th>' . JText::_('COM_BOOKPRO_PDF_HOTEL_NAME') . '</th>
                <th>' . JText::_('COM_BOOKPRO_PDF_STAR_RATE') . '</th>
                <th>' . JText::_('COM_BOOKPRO_PDF_HOTEL_PHONE') . '</th>
                <th>' . JText::_('COM_BOOKPRO_PDF_HOTEL_ADDRESS') . '</th>
                <th>' . JText::_('COM_BOOKPRO_PDF_HOTEL_CITY') . '</th>
                </tr>
                </thead>';

            $itineraryModel = new BookProModelItineraries();
            $lists = array('tour_id' => $this->tour->id);
            $lists['order'] = 'ordering';
            $lists['order_Dir'] = 'DESC';

            $itineraryModel->init($lists);
            $itineraries = $itineraryModel->getData();
            if ($itineraries) {
                for ($t = 0; $t < count($itineraries); $t++) {
                    $packagetypes = TourHelper::getPackages($this->tour->id);
                    $destModel = new BookProModelAirport();
                    $destModel->setId($itineraries[$t]->dest_id);
                    $dest = $destModel->getObject();
                    if ($packagetypes) {
                        $html .='<tbody><tr><td colspan="6" >' . $itineraries[$t]->title . ' - ' . $dest->title . '</td></tr>';

                        for ($i = 0; $i < count($packagetypes); $i++) {
                            $first = 0;
                            $packageHotels = TourHelper::getPackageHotelsByTou_idAndItinerary_idAndPackagetype_id($this->tour->id, $itineraries[$t]->id, $packagetypes[$i]->id);
                            if ($packageHotels) {
                                foreach ($packageHotels as $keys => $packageHotel) {
                                    $hotel = '';
                                    if ($packageHotel->hotel_id) {
                                        $hotelModel = new BookProModelHotel();
                                        $hotelModel->setId($packageHotel->hotel_id);
                                        $hotel = $hotelModel->getObject();
                                    }

                                    $html .='<tr>';
                                    if ($hotel) {
                                        $html .='<td >';
                                        if ($first == 0) {
                                            $packagetypes[$i]->title;
                                        } $first++;
                                        $html .='</td>';
                                        $html .='<td>' . $hotel->title . '</td>';
                                        $html .='<td>';
                                        for ($r = 0; $r < 5; $r++) {
                                            if ($r < $hotel->rank) {
                                                $html .='*';
                                            }
                                        }
                                        $html .='</td>';
                                        $destModel1 = new BookProModelAirport();
                                        $destModel1->setId($hotel->city_id);
                                        $dest1 = $destModel1->getObject();
                                        $html .='<td>';
                                        if ($hotel->phone)
                                            $html .= $hotel->phone;
                                        $html .='</td>';
                                        $html .='<td>';
                                        $html .= $hotel->address1;
                                        $html .='</td>';
                                        $html .='<td>';
                                        $html .= $dest1->title;
                                        $html .='</td>';
                                    } else {
                                        $html .='<td colspan="6">' . $packagetypes[$i]->title . '</td>';
                                    }
                                    $html .='</tr>';
                                }
                            }
                        }
                        $html .='</tbody>';
                    }
                }
            }
            $html .='</table></div>';

            /* var_dump($html); die; */

            $pdf = new TCPDF();
            $pdf->addPage('', 'USLETTER');
            $pdf->setFont('helvetica', '', 12);
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->SetFont('freesans', 'B', 9);
            $pdf->SetTextColor(255);
            $pdf->SetXY(60.5, 24.8);
            $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->setFontSubsetting(false);
            $utf8text = file_get_contents("cache/utf8test.txt", true);
            $pdf->Write(5, $utf8text);
            $pdf->Output('tour.pdf', 'D');
            $pdf->Output('newpdf.pdf', 'D');
            die;
        } else {
            $mainframe->enqueueMessage(JText::_('COM_BOOKPRO_EXPORT_LIST_CANDIDAT_FAILED'), JText::_('COM_BOOKPRO_ERROR'));
        }
    }

}
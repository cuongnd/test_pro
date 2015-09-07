<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('countries', 'application', 'categories', "addons");
AImporter::helper('image', 'date', 'currency');

class BookProViewTourBook extends JViewLegacy {

    // Overwriting JViewLegacy display method
    function display($tpl = null) {
    	$layout = JRequest::getVar('layout');
    	$tpl = JRequest::getVar('tpl');
    	$this->setLayout($layout);


        AImporter::model('tourpackage', 'roomtypes', 'packagetypes');

        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->tour_id=13;
        $cart->saveToSession();
        $app=JFactory::getApplication();
        if(!$cart->tour_id)
        {
            $app->Redirect('index.php');

            return;
        }
        $minmaxperson = $this->getminmaxperson($cart);

        $this->assignRef("minmaxperson", $minmaxperson);


        $packagetypes = $this->getPackagetypes();
        $this->assignRef("packagetypes", $packagetypes);
        $modeltourpackage = new BookProModelTourPackage();
        $modeltourpackage->setId(($cart->package_id ? $cart->package_id : 0));
        $tourpackage = &$modeltourpackage->getObject();

        $this->assign('cart', $cart);

        $this->assignRef("tourpackage", $tourpackage);
        $rtmodel = new BookProModelRoomTypes();
        $listroomtype = $rtmodel->getListRoomTypesByPakage(($tourpackage->id ? $tourpackage->id : 0));
        $this->assignRef("listroomtype", $listroomtype);
        $pivot_listroomtype = JArrayHelper::pivot($listroomtype, 'id');
        $this->assignRef("pivot_listroomtype", $pivot_listroomtype);

        AImporter::model('tour');
        $model_tour = new BookProModelTour();
        $tour = $model_tour->getFullTourObject($cart->tour_id);
        $this->assignRef("tour", $tour);
        $stype = $tour->daytrip ? 'daytrip' : $tour->stype;

        $date_tours = $this->{'getdate_tours_' . $stype}($cart);

        $this->assignRef("date_tours", $date_tours);

        $list_destination_of_tour = $model_tour->getDestination(($tour->id ? $tour->id : 0));
        $this->assign('list_destination_of_tour', $list_destination_of_tour);

        $modeltouraddone = new BookProModelAddons();

        $list_addone = $modeltouraddone->getItems();
        $this->assign('list_addone', $list_addone);

        if ($layout == 'option') {

            $listadultandteenner = $this->getlistadultandteenner($cart->person);
            $listadultandteennerandchildren = $this->getlistadultandteenner($cart->person, true);
            $modeltourpackage = new BookProModelTourPackage();
            $modeltourpackage->setId(($cart->obj_id ? $cart->obj_id : 0));

            $tourpackage = $modeltourpackage->getObject();

            $this->assign('tourpackage', $tourpackage);
            $this->assign('a_listadultandteenner', $listadultandteenner);
            $this->assign('a_listadultandteennerandchildren', $listadultandteennerandchildren);
            $listadultandteenner = AHtmlFrontEnd::getFilterSelect('person', JText::_('COM_BOOKPRO_SELECT_PASSENGER'), $listadultandteenner, null, false);
            $this->assign('listadultandteenner', $listadultandteenner);

            $pivot_list_addone = JArrayHelper::pivot($list_addone, 'id');
            $this->assignRef("pivot_list_addone", $pivot_list_addone);
        }
        if ($layout == '') {

        }
        parent::display($tpl);
    }

    function getPackagetypes() {
        $cart = JModelLegacy::getInstance('TourCart', 'bookpro');
        $cart->load();


        $model_packagetypes = new BookProModelPackageTypes();
        $model_packagetypes->init(array('order' => 'ordering', 'order_Dir' => 'asc'));
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('packagetype.*');
        $query->from('#__bookpro_packagetype AS packagetype');
        $query->leftJoin('#__bookpro_tour_package AS tour_package ON tour_package.packagetype_id=packagetype.id');

        $query->where('tour_package.tour_id=' .($cart->tour_id?$cart->tour_id:0) );
        $query->order('packagetype.ordering asc');
        $query->group('packagetype.title');
        $db->setQuery($query);
        $list = $db->loadObjectList('id');
        return $list;
    }

    function getdate_tours_daytrip($cart, $a_total_person = 0) {
        $total_person = $cart->person->total_person ? $cart->person->total_person : 1;
        $total_person = $a_total_person ? $a_total_person : $total_person;
        $now_year = JFactory::getDate()->format('Y');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        AImporter::model('tour');
        $model_tour = new BookProModelTour();
        $tour = $model_tour->getFullTourObject($cart->tour_id);
        $stype = $cart->stype;

        if ($stype == 'private') {
            $query->select('packagerate.*,tour.capacity AS tour_capacity,tour.stype AS stype,tour_package.min_person AS min_person,tour.id AS tour_id,packagetype.id AS packagetype_id,tour_package.id AS tour_package_id

   ,packagetype.title AS packagetype_title
        ');
            $query->from('#__bookpro_packagerate as packagerate');
            $query->leftJoin('#__bookpro_tour_package  AS tour_package ON tour_package.id=packagerate.tourpackage_id');
            $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=tour_package.packagetype_id');
            $query->leftJoin('#__bookpro_tour AS tour ON tour.id=tour_package.tour_id');
            $query->where('YEAR(packagerate.date) between ' . $now_year . ' and ' . ($now_year + 3));
            $query->where($total_person . ' between SUBSTRING_INDEX( (case when (INSTR(tour_package.min_person,"up") > 0) then REPLACE(tour_package.min_person," up","-100") else tour_package.min_person end), "-", 1 ) and SUBSTRING_INDEX( (case when (INSTR(tour_package.min_person,"up") > 0) then REPLACE(tour_package.min_person," up","-100") else tour_package.min_person end), "-", -1 )');
            $query->where('tour_package.tour_id=' . $tour->id);
            $query->where('tour.daytrip=1');
        } else {
            $query->select('
            packagerate.*,tour.stype AS stype,tour.id AS tour_id
        ');
            $query->from('#__bookpro_packageratedaytripjoingroup as packagerate');
            $query->leftJoin('#__bookpro_tour AS tour ON tour.id=packagerate.tour_id');
            $query->where('YEAR(packagerate.date) between ' . $now_year . ' and ' . ($now_year + 3));
            $query->where('packagerate.tour_id=' . $tour->id);
        }

        $db->setQuery($query);

        $date_tours = $db->loadObjectList();

        $listtour = array();
        foreach ($date_tours as $date_tour) {

            $key_date_tour = JFactory::getDate($date_tour->date)->format('Ymd');

            $listtour[$key_date_tour]['a_class'] = "calendar_avail " . ($date_tour->adult_promo ? " adult_promo " : "");
            $listtour[$key_date_tour]['style'] = "";
            $listtour[$key_date_tour]['enable'] = ($date_tour->close == 1 || $date_tour->request == 1) ? 0 : 1;
            $listtour[$key_date_tour]['atrrib'] = 'packagerate_id="' . $date_tour->id . '" id="' . $key_date_tour . '"';

            //$available = $date_tour->request==1 ? '<div class="available"></div>' : '';
            $request = $date_tour->request == 1 ? '<div class="request"></div>' : '';
            $guaranteed = $date_tour->guaranteed == 1 ? '<div class="guaranteed"></div>' : '';
            $close = $date_tour->close == 1 ? '<div class="close"></div>' : '';

            $listtour[$key_date_tour]['status'] = $request . $guaranteed . $close;
            $listtour[$key_date_tour]['qty'] = '<div class="price">' . str_replace('US', '', CurrencyHelper::formatprice($date_tour->adult_promo ? $date_tour->adult_promo : $date_tour->adult)) . '</div>';
        }

        return $listtour;
    }

    function getdate_tours_private($cart, $a_total_person = 0) {
        $total_person = $cart->person->total_person != 0 ? $cart->person->total_person : 1;
        $total_person = $a_total_person != 0 ? $a_total_person : $total_person;
        $now_year = JFactory::getDate()->format('Y');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('packagerate.*,packagerate.tourpackage_id AS  tourpackage_id,packagerate.adult as adult,tour.title as tour,packagetype.title as packagetype,tour_package.title as tour_package, tour_package.min_person');
        $query->from('#__bookpro_packagerate AS packagerate');
        $query->leftJoin('#__bookpro_tour_package AS tour_package ON tour_package.id=packagerate.tourpackage_id');
        $query->leftJoin('#__bookpro_tour AS tour ON tour.id=tour_package.tour_id');
        $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=tour_package.packagetype_id');
        $query->where('tour_package.packagetype_id=' . ($cart->packagetype_id ? $cart->packagetype_id : 0));
        $query->where('YEAR(date) between ' . $now_year . ' and ' . ($now_year + 3));
        $query->where($total_person . ' between SUBSTRING_INDEX( (case when (INSTR(tour_package.min_person,"up") > 0) then REPLACE(tour_package.min_person," up","-100") else tour_package.min_person end), "-", 1 ) and SUBSTRING_INDEX( (case when (INSTR(tour_package.min_person,"up") > 0) then REPLACE(tour_package.min_person," up","-100") else tour_package.min_person end), "-", -1 )');
        $query->where('tour_package.tour_id=' . ($cart->tour_id ? $cart->tour_id : 0));
        $query->order('date asc');
        $query->group('packagerate.date');
        $db->setQuery($query);
        $date_tours = $db->loadObjectList();
        //echo $query->dump();
        //die;
        $listtour = array();
        foreach ($date_tours as $date_tour) {

            $key_date_tour = JFactory::getDate($date_tour->date)->format('Ymd');

            $listtour[$key_date_tour]['a_class'] = "calendar_avail " . ($date_tour->adult_promo ? " adult_promo " : "");
            $listtour[$key_date_tour]['style'] = "";
            $listtour[$key_date_tour]['enable'] = ($date_tour->close == 1 || $date_tour->request == 1) ? 0 : 1;
            $listtour[$key_date_tour]['atrrib'] = 'packagerate_id="' . $date_tour->id . '" id="' . $key_date_tour . '"';

            //$available = $date_tour->request==1 ? '<div class="available"></div>' : '';
            $request = $date_tour->request == 1 ? '<div class="request"></div>' : '';
            $guaranteed = $date_tour->guaranteed == 1 ? '<div class="guaranteed"></div>' : '';
            $close = $date_tour->close == 1 ? '<div class="close"></div>' : '';

            $listtour[$key_date_tour]['status'] = $request . $guaranteed . $close;
            $listtour[$key_date_tour]['qty'] = '<div class="price">' . str_replace('US', '', CurrencyHelper::formatprice($date_tour->adult_promo ? $date_tour->adult_promo : $date_tour->adult)) . '</div>';
        }


        return $listtour;
    }

    function getdate_tours_shared($cart, $a_total_person = 0) {

        AImporter::model('tour');
        $model_tour = new BookProModelTour();
        $tour = $model_tour->getFullTourObject($cart->tour_id);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $now_year = JFactory::getDate()->format('Y');
        $query->select('packagerate.*,tour.capacity AS tour_capacity,tour.id AS tour_id,packagetype.id AS packagetype_id,tour_package.id AS tour_package_id
   ,date(packagerate.date)  + INTERVAL ' . $tour->days . ' DAY AS date_checkout
   ,packagetype.title AS packagetype_title
        ');
        $query->select('sum(orderinfo.adult+orderinfo.child) as total_adultandchild');
        $query->select('tour.capacity-sum(orderinfo.adult+orderinfo.child) as total_adultandchild_avaible');
        $query->from('#__bookpro_packagerate as packagerate');
        $query->leftJoin('#__bookpro_tour_package  AS tour_package ON tour_package.id=packagerate.tourpackage_id');
        $query->leftJoin('#__bookpro_packagetype AS packagetype ON packagetype.id=tour_package.packagetype_id');
        $query->leftJoin('#__bookpro_tour AS tour ON tour.id=tour_package.tour_id');
        $query->leftJoin('#__bookpro_orderinfo AS orderinfo ON orderinfo.obj_id=packagerate.id');
        $query->leftJoin('#__bookpro_orders AS orders ON orders.id=orderinfo.order_id AND orders.pay_status=' . $db->quote('PENDING'));
        $query->where('YEAR(packagerate.date) between ' . ($now_year - 3) . ' and ' . ($now_year + 3));
        $query->where('tour_package.tour_id=' . $tour->id);

        $query->order('packagerate.date ASC');
        $query->group('packagerate.id');
        $db->setQuery($query);
        $date_tours = $db->loadObjectList();
        $listtour = array();
        foreach ($date_tours as $date_tour) {

            $key_date_tour = JFactory::getDate($date_tour->date)->format('Ymd');
            $listtour[$key_date_tour]['a_class'] = "calendar_avail " . ($date_tour->adult_promo ? " adult_promo " : "");
            $listtour[$key_date_tour]['style'] = "";
            $listtour[$key_date_tour]['atrrib'] = 'packagerate_id="' . $date_tour->id . '" id="' . $key_date_tour . '"';
            $available = $date_tour->request ? '<div class="available"></div>' : '';
            $request = $date_tour->request ? '<div class="request"></div>' : '';
            $guaranteed = $date_tour->guaranteed ? '<div class="guaranteed"></div>' : '';
            $close = $date_tour->close ? '<div class="close"></div>' : '';

            $listtour[$key_date_tour]['status'] = $available . $request . $guaranteed . $close;
            $listtour[$key_date_tour]['qty'] = '<div class="price">' . str_replace('US', '', CurrencyHelper::formatprice($date_tour->adult_promo ? $date_tour->adult_promo : $date_tour->adult)) . '</div>';
        }

        return $listtour;
    }

    function getminmaxperson($cart) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('SUBSTRING_INDEX( tour_package.min_person, "-", 1 ) AS mix_person');
        $query->select('SUBSTRING_INDEX( tour_package.min_person, "-", -1 ) AS max_person');
        $query->select('tour_package.id');
        $query->from('#__bookpro_tour_package as tour_package');
        $query->where('tour_package.tour_id=' . ($cart->tour_id ? $cart->tour_id : 0));
        $query->where('tour_package.packagetype_id=' . ($cart->packagetype_id ? $cart->packagetype_id : 0));
        $db->setQuery($query);

        $list_tour_package = $db->loadObjectList();

        $max_peson = 0;
        $min_person = 9999;
        foreach ($list_tour_package as $tour_package) {
            if ($max_peson < $tour_package->max_person)
                $max_peson = $tour_package->max_person;
            if ($min_person > $tour_package->mix_person)
                $min_person = $tour_package->mix_person;
        }
        $minmax[0] = $min_person;
        $minmax[1] = $max_peson;
        return $minmax;
    }

    function booleanyesnolist($name, $attribs = array(), $selected = null, $yes = 'JYES', $no = 'JNO', $id = false) {
        $arr = array(JHtml::_('select.option', '0', JText::_($no)), JHtml::_('select.option', '1', JText::_($yes)));

        return JHtml::_('select.genericlist', $arr, $name, $attribs, 'value', 'text', (int) $selected, $id);
    }

    function getRoomTypeBox($roomtypesselect) {

        return $list;
    }

    function getlistadultandteenner($listperson, $needasignchildrenforspecialroom = false) {
        $list = array();
        $array_person = array(
            "adult"
            , "teenner"
        );
        if ($needasignchildrenforspecialroom == true) {
            $array_person = array(
                "adult"
                , "teenner"
                , "children"
            );
        }
        if (count($listperson))
            foreach ($listperson as $person_type => $persons) {

                if (in_array($person_type, $array_person)) {
                    for ($i = 0; $i < count($persons); $i++) {
                        $person = new stdClass();
                        $person->value = $person_type . ':' . $i;
                        $person->text = $persons[$i]->firstname . ' ' . $persons[$i]->lastname;
                        $list[] = $person;
                    }
                }
            }
        return $list;
    }

    function getCountrySelect($select) {
        $model = new BookProModelCountries();
        $lists = array('order' => 'id');
        $model->init($lists);
        $list = $model->getData();
        return AHtmlFrontEnd::getFilterSelect('country_id', JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $list, $select, false, 'id="country_id" onchange="changeCountry(this)"', 'id', 'country_name');
    }

    function getPickUpLocation() {
        $model = new BookProModelCategories();
        $lists = array('type' => 9);
        $model->init($lists);
        $list = $model->getData();
        return AHtmlFrontEnd::getFilterSelect('location', JText::_('COM_BOOKPRO_SELECT_PICKUP'), $list, $select, false, '', 'id', 'title');
    }

}

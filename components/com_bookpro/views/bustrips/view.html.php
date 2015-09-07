<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class BookProViewBusTrips extends JViewLegacy
{
    var $context = '';
	function display($tpl = null)
	{
        $input=JFactory::getApplication()->input;
        $country_id=$input->get('country_id',0,'int');
        $destination_id=$input->get('destination_id',0,'int');


        $app    = JFactory::getApplication();
        $params = $app->getParams();
        $layout=$this->getLayout();
        switch( $layout ) {
            case 'country':
                $content_cat_id	= $params->get('content_cat_id', 0);
                $this->articlesContent=$this->getArticlesContent($content_cat_id);
                $filtering_top_destination=$params->get('filtering_top_destination', 'top_car_to_destination');
                $this->top_destination=$this->getTopDestinations($filtering_top_destination);
                $this->popular_car_rental=$this->getPopularCarRental();
                break;
            case 'city':
                $this->destination=$this->getDestination();
                $this->vacation_tip=$this->getListVacationTip();
                $this->feature_car_rental=$this->getFeatureCarRental();
                $this->car_retal_routes=$this->getCarRentalRoutes();
                break;
            case 'search':


                $this->context='com_bookpro.bustrips.search';
                $this->listBusTrip=$this->getListBustrip();
                $this->listCarRentalOffers=$this->getListCarRentalOffers();
                $this->listVehicle=$this->getListVehicle();
                $this->bookingBustrip=$this->getBookingBustrip();
                $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
                $cart->load();

                break;
            case 'booking':
                $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
                $cart->load();
                $this->listNationality=$this->getListNationality();
                $this->listBustripAddOne=$this->getListBustripAddOne($cart->bustrip_id);
                $this->bookingBustrip=$this->getBookingBustrip();

                break;

        }

        $this->_prepare();
		parent::display($tpl);
		
	}

    function getListNationality()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__bookpro_country');
        $query->select('id,country_name as title');
        $query->where('state=1');
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    function getListBustripAddOne($bustrip_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__bookpro_addon');
        $query->select('*');
        $query->where('type='.$query->q('bustrip'));
        $query->where('object_id='.(int)$bustrip_id);
        $db->setQuery($query);
        return $db->loadObjectList();

    }



    function getBookingBustrip($addTotal=false)
    {
        AImporter::table('event');
        $table_event_selected=JTable::getInstance('event','Jtable');
        AImporter::model('bustrips');
        $model_bustrips=&JModelLegacy::getInstance('Bustrips', 'BookproModel');
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $event_id=$cart->event_id;
        $table_event_selected->load($event_id);
        $app=JFactory::getApplication();
        $app->setUserState('bustrip_filter_bustrip_id',$table_event_selected->bustrip_id);
        $listBusTrip		= $model_bustrips->getItems();
        $selectBustrip=$listBusTrip[0];
        if($addTotal)
            $cart->total=$table_event_selected->text;
        $cart->bustrip_id=$selectBustrip->id;
        $cart->saveToSession();
        return $selectBustrip;
    }
    function getListVehicle()
    {
        $listVehicle=array();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('bus.*');
        $query->from('#__bookpro_bus AS bus');
        $db->setQuery($query);
        $listVehicle=$db->loadObjectList();
        return $listVehicle;
    }
    function getListBustrip()
    {
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $from=JFactory::getDate($cart->start)->format('Y-d-m');
        $to=JFactory::getDate($cart->start)->add(new DateInterval('P1D'))->format('Y-d-m');
        $listEvent=file_get_contents(JUri::root().'index.php?option=com_bookpro&controller=bustrips&task=getListBustrip&from='.$from.'&to='.$to);

        $listEvent=json_decode($listEvent);
        AImporter::model('bustrips');
        $model_bustrips=new BookproModelBustrips();

        $app=JFactory::getApplication();
        $app->setUserState('bustrip_filter_bustrip_id',null);
        $from=$cart->pickup?$cart->pickup:$cart->from;
        $to=$cart->dropoff?$cart->dropoff:$cart->to;
        $app->setUserState('bustrip_filter_from',$from);
        $app->setUserState('bustrip_filter_to',$to);
        $listBusTrip=$model_bustrips->getItems();

/*        echo $model_bustrips->getDbo()->getQuery()->dump();
        echo "<pre>";
        print_r($listBusTrip);
        die;*/
        $listBusTripNoEvent=JArrayHelper::pivot($listBusTrip,'id');
        $listBusTrip=array();
        $minRate=$app->getUserState('bustrip_filter_minRate');

        $maxRate=$app->getUserState('bustrip_filter_maxRate');
        foreach($listEvent->data as $key=>$event)
        {


            $price=$event->text;
            $price=(int)$price;
            if(!array_key_exists($event->bustrip_id,$listBusTrip)&&$listBusTripNoEvent[$event->bustrip_id])
            {
                if($minRate&&$price<$minRate)
                    continue;
                if($maxRate&&$maxRate<$price)
                    continue;
                $busTrip=$listBusTripNoEvent[$event->bustrip_id];
                $busTrip->event=$event;
                $listBusTrip[$event->bustrip_id]=$busTrip;

            }
        }

        $this->addFacilities($listBusTrip);
        return $listBusTrip;
    }
    function addFacilities(&$listBusTrip=array())
    {
        if(!count($listBusTrip))
            return false;
        $busIds=array();
        foreach($listBusTrip as $busTrip)
        {
            $busIds[$busTrip->bus_id]=$busTrip->bus_id;
        }
        if(!count($busIds))
            return false;
        $busIds=implode(',',$busIds);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*');
        $query->from('#__bookpro_facility');
        $query->where('type='.$db->q('bus'));
        $query->where('object_id IN('.$busIds.')');
        $db->setQuery($query);
        $listFacilities=$db->loadObjectList();
        foreach($listBusTrip AS $key=>$bustrip)
        {
            foreach($listFacilities as $facility)
            {
                if($facility->object_id==$bustrip->bus_id)
                {
                    $listBusTrip[$key]->facilities[]=$facility;
                }
            }
        }

    }
    function getListCarRentalOffers()
    {
        $app=JFactory::getApplication();
        AImporter::model('bustrips');
        $model_bustrips=new BookproModelBustrips();
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $model_bustrips->setState('filter.featured',1);
        $model_bustrips->setState('filter.from',$cart->from);

        if($cart->pickup)
            $model_bustrips->setState('filter.from',$cart->pickup);
        $model_bustrips->setState('filter.to',$cart->to);

        if($cart->dropoff)
            $model_bustrips->setState('filter.to',$cart->dropoff);
        if($cart->start)
            $model_bustrips->setState('filter.start',$cart->start);
        $listCarRentalOffers		= $model_bustrips->getItems();
        return $listCarRentalOffers;
    }
    function ajaxGetDataPrevDay($tpl)
    {
        parent::display($tpl);
    }
    function parentDisplay($tpl)
    {
        parent::display($tpl);
    }
    function ajaxGetDataNextDay($tpl)
    {
        parent::display($tpl);
    }
    function getCarRentalRoutes()
    {
        $input=JFactory::getApplication()->input;
        $city_id=$input->get('city_id',393,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('bustrip.*');
        $query->from('#__bookpro_bustrip AS bustrip');
        $query->leftJoin('#__bookpro_bus AS bus ON bus.id=bustrip.bus_id');
        $query->leftJoin('#__bookpro_dest AS dest ON dest.id=bustrip.to');
        $query->select('dest.image as dest_image');
        $query->select('bus.title AS bus_title,bus.seat AS bus_seat');
        $query->where('bustrip.from='.$city_id);
        $db->setQuery($query,0,6);
        return $db->loadObjectList();
    }

    function getFeatureCarRental()
    {
        $input=JFactory::getApplication()->input;
        $city_id=$input->get('city_id',393,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('bustrip.*');
        $query->from('#__bookpro_bustrip AS bustrip');
        $query->leftJoin('#__bookpro_bus AS bus ON bus.id=bustrip.bus_id');
        $query->leftJoin('#__bookpro_dest AS dest ON dest.id=bustrip.to');
        $query->select('dest.image as dest_image');
        $query->select('bus.title AS bus_title,bus.seat AS bus_seat');
        $query->where('bustrip.from='.$city_id);
        $db->setQuery($query,0,6);
        return $db->loadObjectList();
    }
    function getDestination()
    {
        $input=JFactory::getApplication()->input;
        $city_id=$input->get('city_id',393,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('dest.*');
        $query->from('#__bookpro_dest AS dest');
        $query->where('id='.$city_id);
        $db->setQuery($query);
        return $db->loadObject();
    }
    function getListVacationTip()
    {
        $input=JFactory::getApplication()->input;
        $city_id=$input->get('city_id',393,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('bustrip.*');
        $query->from('#__bookpro_bustrip AS bustrip');
        $query->leftJoin('#__bookpro_bus AS bus ON bus.id=bustrip.bus_id');
        $query->select('bus.title AS bus_title,bus.seat AS bus_seat');
        $query->where('bustrip.from='.$city_id);
        $db->setQuery($query);

        return $db->loadObjectList();
    }
    function getTopDestinations($filtering_top_destination='top_car_to_destination')
    {
        $input=JFactory::getApplication()->input;
        $destination_id=$input->get('destination_id',0,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);

        $listBustrip=array();
        $filtering_top_destination='all';
        switch( $filtering_top_destination ) {
            case 'top_car_to_destination':

                break;
            case 'top_order':
                break;
            case 'feature':
                break;
            case 'all':

                $query->select('bustrip.*');
                $query->from('#__bookpro_bustrip AS bustrip');
                $query->leftJoin('#__bookpro_dest AS dest_to ON dest_to.id=bustrip.to');
                $query->select('dest_to.image AS dest_image,dest_to.title AS dest_title');
                $query->select('COUNT(bustrip.bus_id) AS total_car');
                $query->group('bustrip.to');
                $query->where('dest_to.country_id='.$destination_id);
                $query->leftJoin('#__bookpro_dest as parent_dest_to ON parent_dest_to.id=dest_to.parent_id');
                $query->select('parent_dest_to.title AS parent_dest_to_title');
                $db->setQuery($query);
                $listBustrip=$db->loadObjectList();


                break;

        }
        return $listBustrip;
    }
    function getPopularCarRental()
    {
        $app    = JFactory::getApplication();
        $params = $app->getParams();
        $number_popular_car_rental=$params->get('number_popular_car_rental', 10);

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $listPopularCarRental=array();
        $query->from('#__bookpro_dest AS dest');
        $query->select('dest.*');
        $db->setQuery($query,0,$number_popular_car_rental);
        $listPopularCarRental=$db->loadObjectList();
        return $listPopularCarRental;
    }
    function getArticlesContent($catId=0)
    {
        $app    = JFactory::getApplication();
        $params = $app->getParams();

        $com_path = JPATH_SITE.'/components/com_content/';
        require_once $com_path.'router.php';
        require_once $com_path.'helpers/route.php';
        JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
        $modelArticle = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
        $modelArticle->setState('params', $params);

        $modelArticle->setState('list.start', 0);
        $modelArticle->setState('list.limit', (int) $params->get('count', 5));

        $modelArticle->setState('filter.published', 1);

        $modelArticle->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
            ' a.modified, a.modified_by, a.publish_up, a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
            ' a.hits, a.featured' );
        // Access filter
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $modelArticle->setState('filter.access', $access);

        // Category filter
        $modelArticle->setState('filter.category_id',$catId );

        // Filter by language
        $modelArticle->setState('filter.language', $app->getLanguageFilter());
        //	Retrieve Content
        $items = $modelArticle->getItems();
        return $items;
    }
	protected function _prepare(){ 
		$document=JFactory::getDocument();
		$document->setTitle(JText::_('COM_BOOKPRO_SELECT_TRIP'));
	}
}

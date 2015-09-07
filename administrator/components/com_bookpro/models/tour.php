<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
AImporter::helper('bookpro', 'model');
AImporter::model('tourcountry','tourcategory');

class BookProModelTour extends AModel
{
    var $_table;
    var $_ids;
    function __construct()
    {
        parent::__construct();
        if (! class_exists('TableTour')) {
            AImporter::table('tour');
        }
        $this->_table = $this->getTable('tour');
    }
    function getFormFieldMapImage()
    {

    }
    function getObject()
    {
        $query = 'SELECT `obj`.* FROM `' . $this->_table->getTableName() . '` AS `obj` ';
        $query .= 'WHERE `obj`.`id` = ' . (int) $this->_id;
        $this->_db->setQuery($query);
        if (($object = &$this->_db->loadObject())) {
            $this->_table->bind($object);
            return $this->_table;
        }

        return parent::getObject();
    }
    function getFullTourObject($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("t.*");
        $query->from('#__bookpro_tour AS t');
        //  $query->where(array(('id='.$id),'state=1'));
        $db->setQuery($query);
        $tour=$db->loadObject();
        //load itinerary
        $query = $db->getQuery(true);
        $query->select("i.*,d.title AS city_title,country.country_name");
        $query->from('#__bookpro_itinerary AS i');
        $query->leftJoin('#__bookpro_tour AS t ON t.id=i.tour_id');
        $query->leftJoin('#__bookpro_dest AS d ON d.id=i.dest_id');
        $query->leftJoin('#__bookpro_country AS country ON country.id = d.country_id');
        $query->where(array('i.state=1','i.tour_id='.$tour->id));
        $query->order('i.ordering');
        $db->setQuery($query);
        $itis=$db->loadObjectList();

        $tour->itis=$itis;
        $query = $db->getQuery(true);
        $query->select("a.*");
        $query->from('#__bookpro_activity AS a');
        $query->innerJoin('#__bookpro_touractivity AS ta ON ta.activity_id=a.id');
        $query->where(array('a.state=1','ta.tour_id='.$tour->id));
        $db->setQuery($query);
        $activities=$db->loadObjectList();
        $tour->activities=$activities;
        $dests = $this->getDestination($tour->id);
        $tour->dests = $dests;
        //load relate trip
        AImporter::helper('tour');
        $relateds = TourHelper::getRelatedTours($tour->id);
        $tour->relateds = $relateds;
        return $tour;
    }
    function getDestination($tour_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('iti.*,dest.title AS desttitle');
        $query->from('#__bookpro_itinerary AS iti ');
        $query->leftJoin('#__bookpro_dest AS dest ON dest.id = iti.dest_id');
        $query->where(array('iti.state=1','iti.tour_id='.$tour_id));
        $query->group('iti.dest_id');
        $db->setQuery($query);
        $itis =  $db->loadObjectList();
        $dests = array();
        foreach ($itis as $iti){
            $tmp = new stdClass();
            $tmp->id = $iti->dest_id;
            $tmp->title = $iti->desttitle;
            $dests[] = $tmp;
            $this->getItiDest($iti->id,$dests);
        }
        return $dests;
    }
    function getItiDest($iti_id,&$dests){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('iti.*,dest.title');
        $query->from('#__bookpro_itinerarydest AS iti ');
        $query->leftJoin('#__bookpro_dest AS dest ON dest.id = iti.dest_id');
        $query->where(array('iti.itinerary_id='.$iti_id));
        $db->setQuery($query);
        $itis =  $db->loadObjectList();
        if(!empty($itis)){
            foreach ($itis as $iti){
                $tmp = new stdClass();
                $tmp->id = $iti->dest_id;
                $tmp->title = $iti->title;
                $dests[] = $tmp;

            }
        }
    }
    function store($data)
    {
        $config = &AFactory::getConfig();
        /* @var $config BookingConfig */
        $id = (int) $data['id'];
        $this->_table->init();
        $this->_table->load($id);
        if (! $this->_table->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        BookProHelper::setSubjectImages($this->_table->images);
        unset($data['id']);
        //die("aa");
        if (! $this->_table->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (! $this->_table->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $tour_id=$this->_table->id;
        //insert activity
        $db    = $this->getDbo();
        //delete tour activity
        $query=$db->getQuery(true);
        $query->delete('#__bookpro_touractivity')
            ->where('tour_id='.(int)$tour_id);
        $db->setQuery($query);
        try
         {
             $db->execute();
         }
         catch (RuntimeException $e)
         {
             $this->setError($e->getMessage());
             return false;
         }
        // delete tour package
        $query=$db->getQuery(true);
        $query->delete('#__bookpro_tourid_packageid')
            ->where('tour_id='.(int)$tour_id);
        $db->setQuery($query);
        try
        {
            $db->execute();
        }
        catch (RuntimeException $e)
        {
            $this->setError($e->getMessage());
            return false;
        }
        // delete tour package
        $query=$db->getQuery(true);
        $query->delete('#__bookpro_tourid_typeid')
            ->where('tour_id='.(int)$tour_id);
        $db->setQuery($query);
         try
         {
             $db->execute();
         }
         catch (RuntimeException $e)
         {
             $this->setError($e->getMessage());
             return false;
         }
        $tuples = array();
        $data=JRequest::get('Post');
        if($data['activity'])
        {
            foreach($data['activity'] as $activity)
            {
                $tuples[] = '(' . (int) $this->_table->id . ',' . (int) $activity . ')';
            }
            $this->_db->setQuery(
                'INSERT INTO #__bookpro_touractivity (tour_id, activity_id) VALUES ' .
                implode(',', $tuples)
            );
            try
            {
                $db->execute();
            }
            catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }
        }
        if($data['pax_group'])
        {
            foreach($data['pax_group'] as $pax_group)
            {
                $tuple[] = '(' . (int) $this->_table->id . ',' . (int) $pax_group . ')';
            }
            $this->_db->setQuery(
                'INSERT INTO #__bookpro_tourid_packageid (tour_id, 	package_id) VALUES ' .
                implode(',', $tuple)
            );
            try
            {
                $db->execute();
            }
            catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }
        }

        if($data['tour_type'])
        {
            foreach($data['tour_type'] as $tour_type)
            {
                $tourtype[] = '(' . (int) $this->_table->id . ',' . (int) $tour_type . ')';
            }
            $this->_db->setQuery(
                'INSERT INTO #__bookpro_tourid_typeid (tour_id,type_id) VALUES ' .
                implode(',', $tourtype)
            );
            try
            {
                $db->execute();
            }
            catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }
        }


        $category_ids=$data['cat_id'];
        //$hotel_ids=$data['hotel_id'];
        $tcatmodel=new BookProModelTourCategory();
        $tcatmodel->store($this->_table->id, $category_ids);
        //$hmodel=new BookProModelTourHotel();
        //$hmodel->store($this->_table->id, $hotel_ids);
        return $this->_table->id;
    }
    function trash($cids)
    {
        /*
        foreach ($cids as $id){

            if( !$this->_table->delete($id))
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $query='DELETE * FROM #__bookpro_tourpackage where tour_id='.$id;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
        */
        foreach ($cids as $id){

            if( !$this->_table->delete($id))
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }
    public function publish(&$pks, $value = 1)
    {
        $user = JFactory::getUser();
        $table = $this->getTable();
        $pks = (array) $pks;

        // Attempt to change the state of the records.
        if (!$table->publish($pks, $value, $user->get('id')))
        {
            $this->setError($table->getError());
            return false;
        }
        return true;
    }
    function unpublish($cids){
        return $this->state('state', $cids, 0, 1);
    }
    public function featured($pks, $value = 0)
    {
        // Sanitize the ids.
        $pks = (array) $pks;
        JArrayHelper::toInteger($pks);
        if (empty($pks))
        {
            $this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
            return false;
        }
        try
        {
            $db = $this->getDbo();
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__bookpro_tour'))
                ->set('featured = ' . (int) $value)
                ->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            $db->execute();
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }
        $this->cleanCache();
        return true;
    }
    function saveorder($cids, $order)
    {
        $branches = array();
        for ($i = 0; $i < count($cids); $i ++) {
            $this->_table->load((int) $cids[$i]);
            $branches[] = $this->_table->parent;
            if ($this->_table->ordering != $order[$i]) {
                $this->_table->ordering = $order[$i];
                if (! $this->_table->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }
        return true;
    }
}
?>
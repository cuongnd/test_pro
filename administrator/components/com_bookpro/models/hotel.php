<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.hotel.component.model');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'model');
    //import needed tables
    AImporter::model('room','rooms','roomrate','roomrates');

    class BookProModelHotel extends AModel
    {
        var $_table;

        var $_ids;

        function __construct()
        {
            parent::__construct();
            if (! class_exists('TableHotel')) {
                AImporter::table('hotel');
            }
            $this->_table = $this->getTable('hotel');
        }

        function getObject()
        {

            $query = 'SELECT `obj`.*,`city`.`title` AS `city_title` FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= 'LEFT JOIN `#__bookpro_dest` AS `city` ON `city`.`id`=`obj`.`city_id` ';
            $query .= 'WHERE `obj`.`id` = ' . (int) $this->_id;

            $this->_db->setQuery($query);

            if (($object = &$this->_db->loadObject())) {
                $this->_table->bind($object);
                $this->_table->city_title = $object->city_title;
                return $this->_table;
            }

            return parent::getObject();
        }


        function store($data)
        {

            $config = &AFactory::getConfig();

            $id = (int) $data['id'];
            $this->_table->init();
            $this->_table->load($id);


            if (! isset($data['images'])) {
                $data['images'] = array();
            }
            if (! $this->_table->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            BookProHelper::setSubjectImages($this->_table->images);
            unset($data['id']);

            if (! $this->_table->check()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if (! $this->_table->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

/*            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->delete('#__bookpro_hotelfacility');
            $query->where('hotel_id='.$this->_table->id);
            $db->setQuery($query);
            $db->execute();
            $facilityselect=$data['facilityselect'];
            if(count($facilityselect)){

                $query=$db->getQuery(true);
                $query->insert('#__bookpro_hotelfacility');

                $query->columns('hotel_id,facility_id,price');

                $hotel_id=$this->_table->id;
                foreach($data['facilities'] as $key_fac=>$facility_price)
                {
                    if($facilityselect[$key_fac]==1)
                    {
                        $facility_price=$facility_price?$facility_price:0;
                        $query->values("$hotel_id,$key_fac,$facility_price");
                    }

                }
                $db->setQuery($query);
                $db->execute();
            } */


            return $this->_table->id;
        }
        function trash($cids)
        {
            foreach ($cids as $id){
                if( !$this->_table->delete($id))
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }else{

                    if($id){
                        //delete rooms
                        $lists = array('hotel_id'=>$id);
                        $roomModel = new BookProModelRooms();
                        $roomModel->init($lists);
                        $rooms = $roomModel->getData();
                        if(count($rooms)>0){
                            for($i1=0; $i1<count($rooms); $i1++){
                                $tableRoom = $this->getTable('room');
                                if(!$tableRoom->delete($rooms[$i1]->id))
                                {
                                    $this->setError($this->_db->getErrorMsg());
                                    return false;
                                }else{
                                    //delete roomrates
                                    if($rooms[$i1]->id){
                                        $lists = array('room_id'=>$rooms[$i1]->id);
                                        $roomrateModel = new BookProModelRoomRates();
                                        $roomrateModel->init($lists);
                                        $roomrates = $roomrateModel->getData();
                                        if(count($roomrates)>0){
                                            for($i2=0; $i2<count($roomrates); $i2++){
                                                $tableRoomrate = $this->getTable('roomrate');
                                                if(!$tableRoomrate->delete($roomrates[$i2]->id))
                                                {
                                                    $this->setError($this->_db->getErrorMsg());
                                                    return false;
                                                }
                                            }
                                        }
                                    }
                                    //delete roomratelog
                                }
                            }
                        }
                        //delete facilities
                    }
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
        function unfeature($cids){
            return $this->state('featured', $cids, 0, 1);
        }
        function feature($cids){
            return $this->state('featured', $cids, 1, 0);
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
            $branches = array_unique($branches);
            foreach ($branches as $group) {
                $this->_table->reorder('parent = ' . (int) $group);
            }
            return true;
        }


    }

?>
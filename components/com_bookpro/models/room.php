<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: room.php 48 2012-07-13 14:13:31Z quannv $
    **/
    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.model');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'model');
    //import needed tables
    AImporter::model('roomrate','roomrates');    

    class BookProModelRoom extends AModelFrontEnd
    {
        var $_table;

        var $_ids;

        function __construct()
        {
            parent::__construct();
            if (! class_exists('TableRoom')) {
                AImporter::table('room');
            }
            $this->_table = $this->getTable('room');
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
        function getObjectByCode($code)
        {

            $query = 'SELECT * FROM '. $this->_table->getTableName() . ' AS obj ';
            $query .= 'WHERE obj.code = "' . $code .'"';

            $this->_db->setQuery($query);

            if (($object = &$this->_db->loadObject())) {
                $this->_table->bind($object);
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

            //insert facility
            $db    = $this->getDbo();
            $query = $db->getQuery(true)
            ->delete('#__bookpro_roomfacility')
            ->where('room_id = ' . (int) $this->_table->id);
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

            if($data['facility'])
            {
                foreach($data['facility'] as $facility)
                {

                    $tuples[] = '(' . (int) $this->_table->id . ',' . (int) $facility . ')';
                }

                $this->_db->setQuery(
                    'INSERT INTO #__bookpro_roomfacility (room_id, facility_id) VALUES ' .
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


            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->delete('#__bookpro_roomfacility');
            $query->where('room_id='.$this->_table->id);
            $db->setQuery($query);
            $db->execute();
             $facilityselect=$data['facilityselect'];
          
            if(count($facilityselect)){
                $query=$db->getQuery(true);
                $query->insert('#__bookpro_roomfacility');

                $query->columns('room_id,facility_id,price');

                $room_id=$this->_table->id;
               
                foreach($data['facilities'] as $key_fac=>$facility_price)
                {  
                    if($facilityselect[$key_fac]==1)
                    {
                        $facility_price=$facility_price?$facility_price:0;
                        $query->values("$room_id,$key_fac,$facility_price");
                    }


                }
                $db->setQuery($query);
                $db->execute();
            }
            return $this->_table->id;
        }

        function trash($cids)
        {             
            foreach ($cids as $id){

                if( !$this->_table->delete($id))
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                //delete roomrate
                if($id){
                    $lists = array('room_id'=>$id);
                    $roomrateModel = new BookProModelRoomRates();
                    $roomrateModel->init($lists);
                    $roomrates = $roomrateModel->getData();      
                    if(count($roomrates)>0){
                        for($i=0;$i<count($roomrates);$i++){
                            $tableRoomrate = $this->getTable('roomrate'); 
                            if(!$tableRoomrate->delete($roomrates[$i]->id))
                            {
                                $this->setError($this->_db->getErrorMsg());
                                return false;
                            } 
                        }     
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
<?php

    defined('_JEXEC') or die;

    jimport('joomla.application.component.modellist');

    class BookProModelFacilities extends JModelList
    {

        public function __construct($config = array())
        {
            if (empty($config['filter_fields'])) {
                $config['filter_fields'] = array(
                    'l.id',
                    'l.title',
                );
            }

            parent::__construct($config);
        }


        /**
        * (non-PHPdoc)
        * @see JModelList::populateState()
        */
        protected function populateState($ordering = null, $direction = null)
        {
        	$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
            $this->setState('filter.search', $search);
            parent::populateState('l.title', 'asc');
        }

        /**
        * (non-PHPdoc)
        * @see JModelList::getListQuery()
        */
        protected function getListQuery()
        {
            $input=JFactory::getApplication()->input;
            $type=$input->get('type','','string');
            $object_id=$input->get('object_id',0,'int');

            $db		= $this->getDbo();
            $query	= $db->getQuery(true);
            $query->select('facility.*');
            $query->from('#__bookpro_facility AS facility');
            if($type)
                $query->where('facility.type='.$db->q($type));
            if($object_id)
                $query->where('facility.object_id='.$object_id);
            if($this->getState('filter.search')){
            	$search = $db->quote('%' . $db->escape($this->getState('filter.search'), true) . '%');
            	$query->where('(facility.title LIKE ' . $search.')');
            }
            return $query;
        }


        function getFacilitiesByIds($Ids){
            $query = 'SELECT fac.* FROM #__bookpro_facility AS fac ';
            if (count($Ids)) {

                $query .='WHERE fac.id IN ('.implode(",", $Ids).')';
            }else{
                $query .='WHERE fac.id IS NULL';
            }
            return $this->_getList($query);
        }

        function getListQueryByFtypeandhotels($hotel_ids,$ftype='')
        {
            $array_hotel=implode(',',$hotel_ids);
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('fac.*,hotel.title AS hotel_name');
            $query->from('#__bookpro_facility AS fac');
            $query->where('fac.hotel_id IN ('.$array_hotel.')');
            $query->leftJoin('#__bookpro_hotel AS hotel ON hotel.id=fac.hotel_id');
            $ftype?$query->where('fac.ftype='.$ftype):null;

            return $this->_getList($query);
        }

}
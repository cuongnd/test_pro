<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.model');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'model');
    //import needed tables
    //AImporter::table('admin');

    class BookProModelAddHotel extends AModel
    {
        var $_table;

        var $_ids;

        function __construct()
        {
            parent::__construct();
            if (! class_exists('TablePackageHotel')) {
                AImporter::table('packagehotel');
            }
            $this->_table = $this->getTable('packagehotel');
        }

        function getObject()
        {
            $query = 'SELECT `packagehotel`.* FROM `' . $this->_table->getTableName() . '` AS `packagehotel` ';
            $query .= 'WHERE `obj`.`id` = ' . (int) $this->_id;
            $this->_db->setQuery($query);
            if (($object = &$this->_db->loadObject())) {
                $this->_table->bind($object);
                $this->_table->tour_title = $object->tour_title;
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


            if (! $this->_table->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            unset($data['id']);



            if (! $this->_table->check()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if (! $this->_table->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return $this->_table->id;
        }
        
        function trash($cids)
        {   
            foreach ($cids as $id){

                if( !$this->table->delete($id))
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }    
            }
            return true;

        }

    }

?>
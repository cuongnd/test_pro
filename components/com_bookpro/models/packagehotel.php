<?php



    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.model');
    //import needed JoomLIB helpers
    AImporter::helper('model');

    class BookProModelPackageHotel extends AModelFrontEnd
    {

        var $_table;

        var $_cache;

        function __construct()
        {
            parent::__construct();
            $this->_table = $this->getTable('packagehotel');
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
            $table = $this->getTable('packagehotel'); 
            foreach ($cids as $id){
                if( !$table->delete($id))
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }                
            }
            return true;           
        }

        function unpublish($cids){
            return $this->state('state', $cids, 0, 1);
        }

        function publish($cids){
            return $this->state('state', $cids, 1, 0);
        }
    }

?>
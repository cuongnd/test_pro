<?php

/**
 * @package 	Bookpro
 * @author 		Sonnv

 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelUser extends JModelList
{

    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('users');

    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from('#__users as a');
        $query->order('name asc');
        $this->setState('list.limit');
        return $query;
    }




  }

?>
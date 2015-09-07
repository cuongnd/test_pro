<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airports.php 100 2012-08-29 14:55:21Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelGalleries extends JModelList {

    var $_table;

    function __construct() {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'g.id',
                'g.title',
            );
        }
        parent::__construct();
        $this->_table = $this->getTable('gallery');
    }

    protected function populateState($ordering = null, $direction = null) {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
        parent::populateState('g.id', 'DESC');
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
            $query->select('g.*')
                    ->from('#__bookpro_gallery AS g');
		if ($obj_id = $this->getState('filter.obj_id')){                    
		            $query->where('g.obj_id =' . $obj_id);
		}  
		if ($type = $this->getState('filter.type')){  
            $query->where('g.type='.$type);
		}
      return $query;
    }

}

?>
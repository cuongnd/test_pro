<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/9/2015
 * Time: 2:57 PM
 */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelPromotions extends JModelList {

    var $_table;

    function __construct() {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'p.id',
                'p.title',
            );
        }
        parent::__construct();
        $this->_table = $this->getTable('promotion');
    }

    protected function populateState($ordering = null, $direction = null) {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
        parent::populateState('p.id', 'DESC');
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
            $query->select('p.*')
                    ->from('#__bookpro_promotion AS p');
      return $query;
    }

}

?>
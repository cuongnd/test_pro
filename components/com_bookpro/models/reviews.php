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



AImporter::helper('request', 'model');
AImporter::model('customers');

class BookProModelReviews extends AModelFrontEnd {

    var $_table;

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'l.id',
                'l.title',
            );
        }

        parent::__construct($config);
    }

    function buildQuery() {
        $query = null;
        if (is_null($query)) {
            $query = "SELECT review.*,CONCAT(c.firstname,' ',c.lastname) AS ufirstname ";
            $query .= 'FROM `#__bookpro_review` AS review ';
            $query .= 'LEFT JOIN #__bookpro_customer AS c ON c.id= review.customer_id ';
            $query .= $this->buildContentWhere();
            //$query .= $this->buildContentOrderBy();
        }
        return $query;
    }

    function buildContentWhere() {
        $where = array();
        $this->addIntProperty($where, 'review-state');
        $this->addIntProperty($where, 'obj_id');
        $this->addIntProperty($where, 'customer_id');
        return $this->getWhere($where);
    }

}

?>
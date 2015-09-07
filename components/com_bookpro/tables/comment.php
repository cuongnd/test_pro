<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TableComment extends JTable {
	var $id;
	var $parentid;
	var $obj_id;
	var $title;
	var $ip;
	var $name;
	var $comment;
	var $date;
	var $ordering;
	var $published;
	var $locked;
	var $email;
	var $website;
	var $star;
	var $userid;
	var $option;
	var $voted;
	var $referer;
	var $p0;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	
     function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_comments', 'id', $db);
    }
	function init() {
		$this->id = 0;
	}

	function check() {

		return true;
	}

}

<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airport.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');

class TableCarDestination extends JTable
{
  
    var $id;
    var $title;
    var $alias;
    var $code;
    var $description;
    var $created;

  
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_car_destination', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
    	$this->title = '';
        $this->alias = '';
        $this->code = '';
        $this->description='';
        $this->created='';
    }
}

?>
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bus.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');

class TableAircraft extends JTable
{
  
    var $id;
    var $code;
    var $title;
    var $seat;
    var $weight;
    var $desc;
    var $image;
    var $state;
    var $agent_id;
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_aircraft', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->title = '';
        $this->image = '';
        $this->state = 1;
              
    }
    function check(){
    	return true;
    }
    
}

?>
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airline.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');

class TableSms extends JTable
{
  
    var $id;
    var $title;
    var $order_id;
    var $content;
    var $from;
    var $to;
    /**
     * 
     * @var status of sms:
     * 0: new 
     * 1: need to send in scheduled time
     * 2: sent success
     * 3: sent failed
     * 4: trashed
     * 5: 
     * 
     */
    var $status;
    var $created;
    // send date time
    var $sent_time;
    var $schedule_time;
    // log
    var $log;
    var $trying;

    // fixed price or percent
       
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_sms', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->title = '';
        $this->content='';
        $this->from = '';
        $this->to = '';
        $this->status = 0;
              
    }
    function check(){
    	$config =& JFactory::getConfig();
    	$tzoffset = $config->getValue('config.offset');
    	if(!$this->id) {
    		$date = JFactory::getDate('now',$tzoffset);
    		$this->created=$date->toSql(true);
    	}else{
    		$date = JFactory::getDate('now',$tzoffset);
    		$this->sent_time=$date->toSql(true);
    	}
    	return true;
    }
}

?>
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');

class TableMailTemplate extends JTable
{
  
    var $id;
    var $title;
    var $type;
    
    var $cus_from_name;
    var $cus_subject;
    var $cus_from_email;
    var $cus_body;
    
    var $ad_subject;
    var $ad_email;
    var $ad_body;
    
    var $code;
       
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_mailtemplate', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
    }
}

?>
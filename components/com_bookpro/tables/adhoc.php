<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customer.php 80 2012-08-10 09:25:35Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

class TableAdhoc extends JTable
{

    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_order_ad_hoc', 'id', $db);
    }


}

?>
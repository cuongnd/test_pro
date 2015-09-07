<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 4/4/2015
 * Time: 10:28 AM
 */

defined('_JEXEC') or die('Restricted access');

class TableUsers extends JTable
{



    function __construct(& $db)
    {
        parent::__construct('#__users', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {

    }

}

?>
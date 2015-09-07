<?php

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.application.component.model');

class TableCpayorderstatus extends JTable
{


    function __construct(& $db)
    {
        parent::__construct('#__bookpro_cpayorderstatus', 'id', $db);
    }

    function init(){
        //$this->state=1;
    }


}
?>

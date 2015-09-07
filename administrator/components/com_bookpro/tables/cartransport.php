<?php


defined('_JEXEC') or die('Restricted access');

class TableCarTransport extends JTable
{
  
    var $id;
    var $code;
    var $from;
    var $to;      
    var $frequency;
    var $start;
    var $end;
    var $created;              
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_car_transport', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->id = 0;
        $this->from='';
        $this->to='';
        $this->code='';
        $this->frequency='';
/*        $this->start='';
        $this->end='';
        $this->created='';*/
       
    }
}

?>
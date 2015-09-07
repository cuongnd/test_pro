<?php


class TableState extends JTable
{
  
   var $id;
	var $country_id = null;
	var $state_name = null;
	var $state_3_code = null;
	var $state_2_code = null;
	var $state	= null;
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_state', 'id', $db);
    }
 function init()
    {
        $this->id = 0;
        $this->country_id = '';
        $this->state=1;
              
    }
}
?>

<?php
class TableRegion extends JTable
{
  
    var $id;
    var $name;
   // var $desc;
    var $state;
   // var $ordering;

   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_region', 'id', $db);
    }
    function init(){
    	$this->id = 0;
        $this->name = '';
        //$this->desc='';
        $this->state=1;
        //$this->ordering='';
    }
}
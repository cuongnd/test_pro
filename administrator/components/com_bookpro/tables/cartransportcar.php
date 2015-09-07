<?php

    // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );


    class TableCarTransportCar extends JTable
    {
        /**
        * Primary Key
        *
        * @var int
        */
        var $id;
        var $car_id;
        var $type;
        var $created;
        var $car_price;


        /**
        * Constructor
        *
        * @param object Database connector object
        */
        function __construct(& $db)
        {
            parent::__construct('#__' . PREFIX . '_car_transport_car', 'id', $db);
        }
        function init()
        {
            $this->id='';
            $this->car_id='';
            $this->type='';
            $this->created='';
            $this->car_price='';        
        }



}
<?php
    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'controller');

    AImporter::model('room', 'roomrate');    

    class BookProControllerDestinationflight extends JControllerForm
    {


        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            
        }

        /**
        * Display default view - Airport list    
        */
        function display()
        {

           

            parent::display();
        }
        


    }

?>
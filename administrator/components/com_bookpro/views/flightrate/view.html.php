<?php

    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('flightrate','flights', 'flightratelogs','flight');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','document','currency');


    class BookProViewFlightRate extends BookproJViewLegacy
    {
        /**
        * Array containing browse table filters properties.
        * 
        * @var array
        */
        var $lists;

        /**
        * Array containig browse table subjects items to display.
        *  
        * @var array
        */
        var $items;

        /**
        * Standard Joomla! browse tables pagination object.
        * 
        * @var JPagination
        */
        var $pagination;


        /**
        * Sign if table is used to popup selecting customers.
        * 
        * @var boolean
        */
        var $selectable;

        /**
        * Standard Joomla! object to working with component parameters.
        * 
        * @var $params JParameter
        */
        var $params;

        function display($tpl = null)
        {        
        	
        	$mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */

            $document = &JFactory::getDocument();

            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $document = &JFactory::getDocument();
            /* @var $document JDocument */
            $model = new BookProModelFlightRate();
            
            $model->setId(ARequest::getCid());
            $obj = &$model->getObject();
           
            $this->_displayForm($tpl, $obj);         
        }


        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            /* @var $document JDocument */

           $flight_id = ARequest::getUserStateFromRequest('flight_id', '', 'int');       
                   //hotel 
                   
            $error = JRequest::getInt('error');
            $data = JRequest::get('post');
            if ($error) {
                $obj->bind($data);

            }

            if (! $obj->id && ! $error) {
                $obj->init();
            }
            
            JFilterOutput::objectHTMLSafe($obj);
            $document->setTitle($obj->title);
            $params = JComponentHelper::getParams(OPTION);
            /* @var $params JParameter */
           
          //  $this->assignRef('rooms', $this->getRoomSelect($obj->flight_id,$flight_id));
            $this->assignRef('pricetype', $this->getPriceTypeSelect($obj->pricetype));
            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);
            //
           
            $model = new BookProModelFlightRateLogs();
            $this->lists = array();
            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit',0, 'int');
            
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
            $this->lists['flight_id'] =  $flight_id;
            $model->init($this->lists);

            $this->pagination = &$model->getPagination();
            $this->items = &$model->getData();
             
            
            if(count($this->items)>0)
            {
                for($i=0; $i<count($this->items); $i++)
                {
                    $item = &$this->items[$i];;
                    $modelRoom = new BookProModelFlight();
                    $room = $modelRoom->getObjectFullById($item->flight_id);
                    if($room){
                        $item->title = $room->stitle;
                    }

                }
            }
            
            parent::display($tpl);
        }
      
        function getPriceTypeSelect($selected){
        	$type = JText::_('COM_BOOKPRO_ROOMRATE_PRICE_TYPE');
        	$types = explode(";", $type);
        	$items = array();
        	foreach ($types as $tp){
        		$items[] = JHtmlSelect::option($tp,JText::_('COM_BOOKPRO_ROOMRATE_PRICE_TYPE_'.$tp));
        	}
        	return JHtmlSelect::genericlist($items, 'pricetype','','value','text',$selected);
        }



    }

?>



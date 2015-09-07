<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('tours', 'tourpackages', 'tourpackage','tour','packageratelogs');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','document','image');
    AImporter::js('view-images');


    class BookProViewPackageRate extends BookproJViewLegacy
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
            $model = new BookProModelPackageRate();
            $model->setId(ARequest::getCid());
            $obj = &$model->getObject();
            //var_dump($obj);
            $this->_displayForm($tpl, $obj);     
            
        }


        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            /* @var $document JDocument */

           $tour_id = ARequest::getUserStateFromRequest('tour_id', '', 'int');       
                   
             if($tour_id){   
                $modelTour = new BookProModelTour();        
                $modelTour->setId($tour_id);
                $this->tour = $modelTour->getObject();                  
             }
                         
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
                
            $this->assignRef('obj', $obj);
            
            $this->assignRef('params', $params);
          

            $model = new BookProModelPackageRateLogs();
            $this->lists = array();
            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit',0, 'int');
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
            $this->lists['tour_id'] = ARequest::getUserStateFromRequest('tour_id', '', 'int');  
            $model->init($this->lists);
			
            $this->pagination = &$model->getPagination();
            $this->items = &$model->getData();
            $this->assignRef('tourpackages', $this->getTourPackageSelect($obj->tourpackage_id,$this->lists['tour_id']));
            $this->assignRef("roomtypes",$this->getRoomTypeBox());
           // var_dump($this->assignRef("roomtypes",$this->getRoomTypeBox(array))) ;
            if(count($this->items)>0)
            {
               
                for($i=0; $i<count($this->items); $i++)
                {
                    $item = &$this->items[$i];;
                    $modelTourPackage = new BookProModelTourPackage();
                    $modelTourPackage->setId($item->tourpackage_id);
                    $tourpackage = $modelTourPackage->getObject();
                    
                    
                    if($tourpackage){
                        $item->packagetitle = $tourpackage->packagetitle;
                    }

                    $startdate='';
                    if($item->startdate !='0000-00-00 00:00:00')
                        $startdate=JFactory::getDate($item->startdate)->format('d F Y');
                    $item->startdate = $startdate;

                    $enddate='';
                    if($item->enddate !='0000-00-00 00:00:00')
                        $enddate=JFactory::getDate($item->enddate)->format('d F Y');
                    $item->enddate = $enddate;


                }
            }
                   
            parent::display($tpl);
        }
        function getTourPackageSelect($select,$tour_id){ 
            
            $model = new BookProModelTourPackages();
            $param=array('tour_id'=>$tour_id);
            
            $model->init($param);
            $list=$model->getData();
			            
            return AHtml::getFilterSelect('tourpackage_id', 'COM_BOOKPRO_SELECT_TOUR_PACKAGE', $list, $select, '', '', 'id', 'packagetitle');
        }
       
        function getRoomTypeBox($name='room_id[]'){
            AImporter::model('roomtypes');
            $model = new BookProModelRoomTypes();  
            $model->init($param);
            $list=$model->getData();  
             
           //var_dump($list);  exit();
           return AHtml::getFilterSelect($name, 'Room Types', $list, $select, '', '', 'id', 'title');
                
             
        }



    }

?>
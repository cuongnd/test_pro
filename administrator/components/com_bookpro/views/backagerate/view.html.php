<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('packagerate','packages', 'packages', 'package', 'packageratelogs','tour');
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
            $model = new BookProModelPackagerate();
            $model->setId(ARequest::getCid());
            $obj = &$model->getObject();
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
            $this->assignRef('packages', $this->getPackageSelect($obj->package_id,$tour_id));      
            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);
            //

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
            if(count($this->items)>0)
            {
                for($i=0; $i<count($this->items); $i++)
                {
                    $item = &$this->items[$i];;
                    $modelPackage = new BookProModelPackage();
                    $modelPackage->setId($item->package_id);
                    $package = $modelPackage->getObject();
                    if($package){
                        $item->package_id = $package->title;
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
        function getPackageSelect($select,$tour_id){                  
            $model = new BookProModelPackages();
            $param=array('tour_id'=>$tour_id);
            $model->init($param);
            $list=$model->getData();
            return AHtml::getFilterSelect('package_id', 'COM_BOOKPRO_SELECT_PACKAGE', $list, $select, '', '', 'id', 'title');
        }



    }

?>
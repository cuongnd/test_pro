<?php


    defined('_JEXEC') or die('Restricted access');
    AImporter::helper('request', 'controller', 'tour');
    AImporter::model('packageratedetail', 'tourpackages');
    //import needed JoomLIB helpers

    class BookProControllerCloseTour extends AController
    {            
        var $_model;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_model = $this->getModel('packageratedetail');
            $this->_controllerName = closetour;
        }

        /**
        * Display default view - Airport list	
        */
        function display()
        {
            switch ($this->getTask()) {
                case 'publish':
                    $this->state($this->getTask());
                    break;
                case 'unpublish':
                    $this->state($this->getTask());
                    break;
                case 'trash':
                    $this->state($this->getTask());
                    break;
                default:
                    JRequest::setVar('view', 'packageratedetail');
            }

            parent::display();
        }

        function save($apply = false)
        {
            JRequest::checkToken() or jexit('Invalid Token');
            $mainframe  = &JFactory::getApplication();
            $post       = JRequest::get('post');
              
            $modelTourPackages = new BookProModelTourPackages(); 
            $lists = array('tour_id'=>$post['tour_id']);
            $modelTourPackages->init($lists);  
            $tourPackages = $modelTourPackages->getData();
            
            $fromdate1 = new JDate($post['from']);
            $todate1 = new JDate($post['to']);
            $fromToto = $fromdate1->diff($todate1)->days;

            if($tourPackages){
                foreach($tourPackages as $key => $tourpackage){
                    for ($i = 0; $i <= $fromToto; $i++) {
                        $date = date('Y-m-d', strtotime($from . $i . ' day'));
                        $packagerate = TourHelper::getPackageRateByTourPackageIdAndDate($tourpackage->id, $date, $post['tour_id']);

                         $data = (array)$packagerate;
                         $data['date']          = $date;
                         $data['available']     = $post['available'];
                         $data['request']       = $post['request'];
                         $data['guaranteed']    = $post['guaranteed'];
                         $data['close']         = $post['close'];
                                                     
                        if($packagerate){ 
                            $model = new BookProModelPackageRateDetail();
                            $ids   = $model->store($data);       
                        }
                    }                               
                }
                
            } 
            
                                 
            if ($ids !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }          
                $mainframe->redirect('index.php?option=com_bookpro&view=closetour&tour_id='.$post['tour_id'].'&tmpl=component');
            
        }
        function CloseTourByFromAndTo()
        {
            
            AImporter::helper('request', 'controller', 'tour');
            AImporter::model('packageratedetail', 'tourpackages');
    
            $from = ARequest::getUserStateFromRequest('from', '', 'string');
            $to = ARequest::getUserStateFromRequest('to', '', 'string');
            $tour_id = ARequest::getUserStateFromRequest('tour_id', '', 'int');
            $tourpackage_id = ARequest::getUserStateFromRequest('tourpackage_id', '', 'int');

            $modelTourPackages = new BookProModelTourPackages(); 
            $lists = array('tour_id'=>$tour_id);
            $modelTourPackages->init($lists);  
            $tourPackages = $modelTourPackages->getData();
            
            $fromdate1 = new JDate($from);
            $todate1 = new JDate($to);

            $fromToto = $fromdate1->diff($todate1)->days;

            for ($i = 0; $i <= $fromToto; $i++) {
                $datet = date('Y-m-d', strtotime($from . $i . ' day'));
                $packagerate = TourHelper::getPackageRateByTourPackageIdAndDate($tourpackage->id, $date, $tour_id);

                $model = new BookProModelPackageRateDetail();
                $ids   = $model->store($data);          
            }            
            
            echo $tour_id;
            exit();
        }             
    }

?>
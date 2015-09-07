<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries

    //import needed models
    AImporter::model('tour','categories','activities','tourcategory','insurances','airports','flights','countries','tourpackages','packagetypes');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','image','file');

    class BookProViewTour extends BookproJViewLegacy
    {
        protected $form = null;
        function display($tpl = null)
        {
            
            //JForm::addFormPath(JPATH_COMPONENT_BACK_END.'/models/forms'); // set destination directory of xml maniest
            //$this->form = JForm::getInstance('com_bookpro.tour', 'tour', array('load_data' => $loadData));
            /* @var $form JForm */
            
            $mainframe = JFactory::getApplication();
            /* @var $mainframe JApplication */
            $document = JFactory::getDocument();
            /* @var $document JDocument */
            $model = new BookProModelTour();
            $model->setId(ARequest::getCid());
            $obj = $model->getObject();
            $this->_displayForm($tpl, $obj);

        }

        /**
        * Prepare to display page.
        *
        * @param string $tpl name of used template
        * @param TableCustomer $customer
        * @param JUser $user
        */
        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            /* @var $document JDocument */
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
            $country_ids=array();
            $cat_ids=array();
            $hotel_ids=array();
            if($obj->id){
                $selected_cats=$this->getCategorySelected($obj->id);
                foreach ($selected_cats as $cat)
                {
                    $cat_ids[]=$cat->cat_id;
                }
            }
            $activiies=BookProHelper::getActivitiesById($obj->id);
            $this->assignRef('hotelfacility', $this->getActivities($activiies));

            $groups=BookProHelper::getGroupById($obj->id);
            $this->assignRef('group_size', $this->getGroups($groups));

            $tour_type=BookProHelper::getTourTypeId($obj->id);
            $this->assignRef('tour_type', $this->getTourType($tour_type));

            $pack_type=BookProHelper::getPackageTypesId($obj->id);
            $this->assignRef('pack_type', $this->getPackageTypes($pack_type));

            //$this->hotels=$this->getHotelBox($obj->hotel_id);
            $categories=$this->getCategoryBox($cat_ids);
            $this->assignRef("categories",$categories);
            //$this->assignRef("insurances",$this->getInsuranceBox($obj->insurance_id));
            $this->assignRef("departs",$this->getDepartLocation($obj->departure_id));
            $this->assignRef("country_id",$this->getCountries($obj->country_id));
            $this->assignRef("min_person_id",$this->getTourPackages($obj->min_person_id));
           // var_dump($this->assignRef("country_id",$this->getCountries($obj->country_id)));die();
            $this->duration= $this->getDurationBox($obj->duration);
            $this->days = $this->getDays($obj->days);
            $this->days_daytrip = $this->getDaysDaytrip($obj->days);
            parent::display($tpl);
        }
        function getCategoryBox($select){
            $model = new BookProModelCategories();
            $lists = array('type' => TOUR);
            $model->init($lists);
            $items = $model->getData();
            return AHtml::getFilterSelect('cat_id[]', JText::_('COM_BOOKPRO_TOUR_CATEGORY_SELECT'), $items, $select, false, 'multiple="multiple" size="10" class="required"', 'id', 'title');
        }
        function getCountries($select){
            $model = new BookProModelCountries();
            $items = $model->getItems();
            //var_dump($items);die();
            return AHtml::getFilterSelect('country_id', JText::_('COM_BOOKPRO_TOUR_COUNTRY_SELECT'), $items, $select, false, 'class="required"', 'id', 'country_name');
        }
        function getTourPackages($select){
            $model = new BookProModelTourPackages();
            $items = $model->getData();
            //var_dump($items);die();
            return AHtml::getFilterSelect('min_person_id', JText::_('COM_BOOKPRO_TOUR_MIN_PERSON_SELECT'), $items, $select, false, 'class="required"', 'id', 'min_person');
        }

        function getDepartLocation($select){
            $model = new BookProModelAirports();
            $items = $model->getItems();
            return AHtml::getFilterSelect('departure_id', JText::_('COM_BOOKPRO_TOUR_DEPARTURE_SELECT'), $items, $select, false, 'class="required"', 'id', 'title');
        }

		function getDays($selected){
			$options = array();
			for ($i = 2;$i <=50;$i++){
				$options[] = JHtmlSelect::option($i,JText::sprintf('COM_BOOKPRO_TOUR_DAY',$i));
				
			}		
			return JHtmlSelect::genericlist($options, 'days','','value','text',$selected,'days');
		}

    	function getDaysDaytrip($selected){
			$options = array();
			$options[] = JHtmlSelect::option(0.5,JText::sprintf('COM_BOOKPRO_TOUR_DAY','1/2'));
			$options[] = JHtmlSelect::option(1,JText::sprintf('COM_BOOKPRO_TOUR_DAY','1'));
			return JHtmlSelect::genericlist($options, 'days','','value','text',$selected,'days_daytrip');
		}
				
        function getCategorySelected($tour_id){
            $model=new BookProModelTourCategory($tour_id);
            $lists= array('tour_id' => $tour_id);
            $model->init($lists);
            $fullList = $model->getData();
            return $fullList;
        }
        function getDurationBox($select){
            $model = new BookProModelCategories();
            $lists=array('state'=>1);
            $model->init($lists);
            $items = $model->getAll(DURATION);
            return AHtml::getFilterSelect('duration',JText::_('COM_BOOKPRO_TOUR_SELECT_DURATION'), $items, $select, false, '', 'value', 'text');
        }
        function getActivities($activities){
            $model = new BookProModelActivities();
            $list=$model->getItems();
            return AHtml::bootrapCheckBoxList($list,'activity[]','',$activities,'id', 'title');
        }
        function getGroups($groups){
            $model = new BookProModelTourPackages();
            $list=$model->getData();
            return AHtml::bootrapCheckBoxList($list,'pax_group[]','',$groups,'id', 'title');
        }
        function getTourType($tourtype){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('t.*');
            $query->from('#__bookpro_tour_type AS t');
            $db->setQuery($query);
            $list=$db->loadObjectList();
            return AHtml::bootrapCheckBox($list,'tour_type[]','',$tourtype,'id', 'title');
        }
        function getPackageTypes($packagetypes)
        {
            $model = new BookProModelPackageTypes();
            $list = $model->getData();
            return AHtml::bootrapCheckBox($list, 'package_types_id[]', '', $packagetypes, 'id', 'title');
        }
        function getInsuranceBox($select){
            $model=new BookProModelInsurances();
            $lists= array('order' => 'price');
            $model->init($lists);
            $items = $model->getData();
            return AHtml::getFilterSelect('insurance_id', 'Select Insurance', $items, $select, false, '', 'id', 'title');
        }
        function getHotelBox($select){
            $model = new BookProModelHotels();
            $lists=array('state'=>1);
            $model->init($lists);
            $items = $model->getData();
            return AHtml::getFilterSelect('hotel_id', JText::_('COM_BOOKPRO_TOUR_PACKAGE_SELECT_HOTEL'), $items, $select, false, '', 'id', 'title');
        }

    }

?>
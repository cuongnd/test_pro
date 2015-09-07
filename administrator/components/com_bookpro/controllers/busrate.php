<?php





defined('_JEXEC') or die('Restricted access');



//import needed JoomLIB helpers

AImporter::helper('request', 'controller');

AImporter::model('busrates', 'busrate', 'busratelog','bustrip');



class BookProControllerbusRate extends AController

{





	var $_model;



	function __construct($config = array())

	{

		parent::__construct($config);

		$this->_model = $this->getModel('busrate');

		$this->_controllerName = 'busrate';

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

				JRequest::setVar('view', 'busrate');

		}



		parent::display();

	}
    function loadData()
    {
        include(JPATH_ROOT.'/administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/connector/scheduler_connector.php');//includes the file
        $input=JFactory::getApplication()->input;
        $bustrip_id=$input->get('bustrip_id',0,'int');
        $j_cfg = new JConfig;
        $res=mysql_connect($j_cfg->host, $j_cfg->user, $j_cfg->password);
        mysql_select_db($j_cfg->db);

        $scheduler = new JSONSchedulerConnector($res);
        $scheduler->render_sql(
            "SELECT * from ".$j_cfg->dbprefix."bookpro_events_rec"." WHERE bustrip_id=".$bustrip_id,
            'id',
            'event_start,event_end,text,rec_type,rec_pattern,event_length,bustrip_id',
            false
        );
        //$scheduler->render_table($j_cfg->dbprefix."bookpro_events_rec","id","event_start,event_end,text,rec_type,rec_pattern,event_length,bustrip_id","type");

    }
    function saveData()
    {
        $input=JFactory::getApplication()->input;
        include(JPATH_ROOT.'/administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/connector/scheduler_connector.php');//includes the file

        $j_cfg = new JConfig;
        $res=mysql_connect($j_cfg->host, $j_cfg->user, $j_cfg->password);
        mysql_select_db($j_cfg->db);
        $scheduler = new schedulerConnector($res);

        $scheduler->render_table($j_cfg->dbprefix."bookpro_events_rec","id","event_start,event_end,text,rec_type,rec_pattern,event_length,bustrip_id","type");



        //$calendar = new schedulerConnector($res);//connector initialization
        //$calendar->configure($j_cfg->dbprefix."bookpro_events_rec","id","event_start,event_end,text,rec_type,rec_pattern","type");
        //$calendar->render();
        die;
        //$calendar->render_table($j_cfg->dbprefix."bookpro_events_rec","id","event_start,event_end,text,rec_type,rec_pattern","type");





        die;
    }
    function renderDataTest()
    {

        echo "completed";
        die;
    }
    protected function getUserGroups() {
        $user =& JFactory::getUser();
        $gr = Array();
        if (isset($user->groups)) {
            // multiple user groups
            $groups = $user->groups;
            foreach ($groups as $k => $v)
                $gr[] = (string) $v;
        } else {

            switch($user->usertype) {
                case '':
                    $usertype = null;
                    break;
                case 'Super Administrator':
                    $usertype = 'superadministrator';
                    break;
                default:
                    $usertype = strtolower($user->usertype);
            }
            if ($usertype)
                $gr[] = $usertype;
        }
        return $gr;
    }
	/**

	 * Open editing form page

	 */

	function editing()

	{

		parent::editing('busrate');

	}


    function  ajaxChangePrice()
    {
        AImporter::helper('currency');
        $input=JFactory::getApplication()->input;
        $data_date=$input->get('data_date','','string');
        $date_class=JFactory::getDate($data_date)->format('Y-m-d');
        $bustrip_id=$input->get('data_bustrip_id','','string');
        $price=$input->get('price',0,'int');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__bookpro_busrate');
        $query->set('adult='.$price);
        $query->where('date='.$db->q($data_date));
        $query->where('bustrip_id='.$bustrip_id);
        $db->setQuery($query);
        $respone_array=array();
        if($db->execute())
        {
            $contents=CurrencyHelper::displayPrice($price,0);
            $respone_array[] = array(
                'key' => '.display-price-'.$date_class,
                'contents' => $contents
            );
            $contents=JText::_('Save successful');
            $respone_array[] = array(
                'key' => '.display-error-'.$date_class,
                'contents' => $contents
            );

        }
        else
        {
            $contents=JText::_('Cannot save');
            $respone_array[] = array(
                'key' => '.display-error-'.$date_class,
                'contents' => $contents
            );
        }
        echo json_encode($respone_array);
        die;
    }
	/**

	 * Cancel edit operation. Check in subject and redirect to subjects list.

	 */

	function cancel()

	{

		$mainframe = &JFactory::getApplication();

		$mainframe->enqueueMessage(JText::_('Subject editing canceled'));

		$mainframe->redirect('index.php?option=com_bookpro&view=bustrips');

	}







	/**

	 * Move item up in ordered list

	 */

	function orderup()

	{

		$this->setOrder(- 1);

	}



	/**

	 * Move item down in ordered list

	 */

	function orderdown()

	{

		$this->setOrder(1);

	}





	/**

	 * Save subject and state on edit page.

	 */

	function apply()

	{

		$this->save(true);

	}





	/**

	 * Save subject.

	 *

	 * @param boolean $apply true state on edit page, false return to browse list

	 */

	function save($apply = false)

	{

		JRequest::checkToken() or jexit('Invalid Token');

		$mainframe = &JFactory::getApplication();

		

		$input=JFactory::getApplication()->input;

		

		$post = JRequest::get('post');

		

		$startdate = new JDate($input->get('startdate',0));

		$enddate   = new JDate($input->get('enddate',0));

		$starttoend =  $startdate->diff($enddate)->days;

		//delete old record

		$model=new BookProModelbusRate();

		if($this->deleteRate($startdate, $enddate, $input->get('bus_id'))){

			

			$db=JFactory::getDbo();
			$query=$db->getQuery(true);

			$query->insert('#__bookpro_busrate');

			$query->columns('bustrip_id,date,adult,child,infant,adult_roundtrip,child_roundtrip,infant_roundtrip,discount');

			$values=array();

			

			for($i=0; $i <= $starttoend; $i++)

			{

				$temp=array( $input->get('bus_id'),$db->quote($startdate->toSql()),$input->get('adult',0),$input->get('child',0),$input->get('infant',0),

						$input->get('adult_roundtrip',0),$input->get('child_roundtrip',0),$input->get('infant_roundtrip',0),$input->getFloat('discount',0));

				$values[]=implode(',', $temp);

				$startdate=$startdate->add(new DateInterval('P1D'));

			}

			$query->values($values);

			$db->setQuery($query);

			$db->execute();

			

			if ($id !== false) {

				$this->savebusratelog($post);

				$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');

			} else {

				$mainframe->enqueueMessage(JText::_('Save failed'), 'error');

			}

		}else{

			$mainframe->enqueueMessage(JText::_('Can not delete bus rate'), 'error');

		}

		ARequest::redirectList('busrate');



	}

	function deleteRate($from,$to,$bus_id){

		try {

			$db=JFactory::getDbo();

			$query=$db->getQuery(true);

			$query->delete('#__bookpro_busrate')->where(array('bustrip_id='.$bus_id,'date BETWEEN '.$db->quote($from).' AND '.$db->quote($to)));
			$db->setQuery($query);

			$db->execute();

			return true;

		}catch (Exception $e){

			

			return false;

		}

	}



	function savebusratelog($data)

	{

		$model = new BookProModelbusratelog();

		$id = $model->store($data);

		return $id;

	}





}



?>
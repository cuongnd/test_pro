<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id$
    **/
    AImporter::helper('request','controller');
    class BookProControllerCustomer extends JControllerLegacy{

        var $_model;
		protected $test;
        function __construct($config = array())
        {

            parent::__construct($config);
            if (! class_exists('BookProModelCustomer')) {
                AImporter::model('customer');
            }
            $this->_model = new BookProModelCustomer();
        }

        function display()
        {
            switch ($this->getTask()) {
                case 'trash':
                case 'restore':
                    $this->state($this->getTask());
                    break;
                case 'detail':
                    JRequest::setVar('view', 'customer');
                    break;
                default:
                    JRequest::setVar('view', 'customers');
                    break;
            }
            parent::display();
        }

        /**
        * Display browse customers page into element window.
        */

        function element()
        {
            $this->display();
        }
        function checkoder()
        {
            $db=JFactory::getDbo();
            $app=JFactory::getApplication();
            $input=JFactory::getApplication()->input;
            $order_number=$input->get('order_number');
            $query=$db->getQuery(true);
            $query->select('a_order.id');
            $query->from('#__bookpro_orders AS a_order');
            $query->where('a_order.order_number='.$order_number);
            $db->setQuery($query);
            $result=$db->loadResult();
            echo $result;
            exit();
        }
        function add(){

            JRequest::setVar('view', 'customer');
            JRequest::setVar('layout', 'form');
            parent::display();
        }

        function edit(){

            JRequest::setVar('view', 'customer');
            JRequest::setVar('layout', 'form');
            parent::display();
        }
        function editing()
        {
            parent::editing('customer');
        }

        /**
        * Cancel edit operation. Check in customer and redirect to customers list.
        */
        function cancel()
        {
            parent::cancel('Customer editing canceled');
        }
        function ajaxIsExistsEmailInUserSystem()
        {


			$input=JFactory::getApplication()->input;
        	$post=$input->getArray($_POST);
        	AImporter::model('customer');
        	$model_customer=new BookProModelCustomer();
        	$user=$model_customer->getUserSystemByEmail($post['email']);

        	$return=new stdClass();
        	if($user)
        	{
        		$return->error= 'true';
        		$return->errorMessage="<b>The Email you chose is already.</b>  Please enter another email.";

        	}
        	else
        	{
        		$return->error= 'false';
        		$return->errorMessage='';

        	}
        	echo json_encode($return);
        	exit();

        }

        function ajaxCheckAllowLoginWithUsernameSystem()
        {
	     	$input=JFactory::getApplication()->input;
        	$post=$input->getArray($_POST);
        	$user=JUserHelper::getUserId($post['username']);
        	$return=new stdClass();
        	if($user)
        	{
        		$return->error= 'false';
        		$return->errorMessage='';

        	}
        	else
        	{
        		$return->error= 'true';
        		$return->errorMessage="<b>Customer not exists.</b>  Please check again.";


        	}
        	echo json_encode($return);
        	exit();

        }
        function ajax_login()
        {

        	$app=JFactory::getApplication('site');
        	$input=$app->input;
        	$post=$input->getArray($_POST);
        	$credentials['username']=$post['username'];
        	$credentials['password']=$post['passwd'];
        	//$credentials=array('username'=>'cuongnd','password'=>'123456');
        	$error=$app->login($credentials,array('remember'=>true));
        	$user=JFactory::getUser();
        	$return=new stdClass();
        	if($user->id)
        	//if($post['username']=='cuongnd'&&$post['passwd']=='123456')
        	{
        		$return->error= 'false';
        		$return->errorMessage='';

        	}
        	else
        	{
        		$return->error= 'true';
        		$return->errorMessage="Please check pass again.";


        	}
        	echo json_encode($return);
        	exit();

        }
        function ajaxIsUsernameExistsInUserSystem()
        {


        	$input=JFactory::getApplication()->input;
        	$post=$input->getArray($_POST);
        	$user=JUserHelper::getUserId($post['newusername']);
        	$return=new stdClass();
        	if($user)
        	{
        		$return->error= 'true';
        		$return->errorMessage="<b>The User Name you chose is already in use.</b>  Please enter another name.";

        	}
        	else
        	{
        		$return->error= 'false';
        		$return->errorMessage='';

        	}
        	echo json_encode($return);
        	exit();

        }
        private function changepassword()
        {
            $mainframe = &JFactory::getApplication();
            $return = JRequest::getVar('return',0);
            $return = base64_decode($return);
            $user_data = $_POST;
            if($user_data['password'] == $user_data['password2']){
                $user = JFactory::getUser();
                $salt = JUserHelper::genRandomPassword(32);
                $crypt = JUserHelper::getCryptedPassword(JString::trim($user_data['password']), $salt);
                $password = $crypt.':'.$salt;
                $user->set('password',  $password);
                if($user->save()) $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            }else  JError::raiseWarning('', JText::_(' Passwords do not match. Please re-enter password.'));

            $config = &AFactory::getConfig();
            $acontroller = new AController();
            $groupUser = $acontroller->checkGroupForUser();

            if($config->supplierUsergroup == $groupUser){
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=supplierpage&form=password&Itemid='.JRequest::getVar('Itemid'));
            }else{
                $this->setRedirect($return);
            }
        }

        function socialregister(){

        	$input=JFactory::getApplication()->input;
        	$type=$input->get('social');
        	$dispatcher	= JDispatcher::getInstance();
        	JPluginHelper::importPlugin('bookpro');
        	$results = $dispatcher->trigger('onBookproCustomerRegister', array($type));
        	return;

        }

        function save($apply = false)
        {
            JRequest::checkToken() or jexit('Invalid Token');

            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $user = &JFactory::getUser();
            /* @var $user JUser */
            $config = &AFactory::getConfig();
            $post = JRequest::get('post');
            if ($user->id) {
                $this->_model->setIdByUserId();
                $post['id'] = $this->_model->getId();
            } else
                $post['id'] = 0;

            $isNew = $post['id'] == 0;
            $id = $this->_model->store($post);

            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            }else{
                $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
            }

            $config = &AFactory::getConfig();
            $acontroller = new AController();
            $groupUser = $acontroller->checkGroupForUser();

            if($config->supplierUsergroup == $groupUser){
                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=supplierpage&form=profile&Itemid='.JRequest::getVar('Itemid'));
            }else{
                $mainframe->redirect('index.php?option=com_bookpro&view=mypage&form=profile');
            }

        }
        function ajaxsave($apply = false)
        {
            $config=AFactory::getConfig();
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $user = &JFactory::getUser();
            /* @var $user JUser */
            $config = &AFactory::getConfig();
            $post = JRequest::get('post');


            if ($user->id) {
                $this->_model->setIdByUserId();
                $post['id'] = $this->_model->getId();
            } else
                $post['id'] = 0;


            $post['name'] = $post['firstname'].$post['lastname'];
            $isNew = $post['id'] == 0;

            $id = $this->_model->store($post);

            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');

            }
            if($config->autoLogin){
                $options = array();
                $options['remember'] = JRequest::getBool('remember', true);
                $credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
                $credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
                $error = $mainframe->login($credentials, $options);
            }

            echo $id;
            die;

        }
        function getcustomer(){

            if(!class_exists('BookProModelCustomer')){
                AImporter::model('customer');
            }
            $user=JFactory::getUser();
            //echo json_encode($user);
            //die() ;
            $model=new BookProModelCustomer();
            $model->setIdByUserId();
            $customer=$model->getObject();
            echo json_encode($customer);
            die() ;

        }
        function cancel_order(){
            $mainframe=JFactory::getApplication('site');
            $order_id=JRequest::getVar('order_id');
            if (! class_exists('BookProModelOrder')) {
                AImporter::model('orders');
            }
            $model= new BookProModelOrders();
            $model->setId($order_id);
            $order=$model->getObject();
            $order->order_status='CANCELLED';
            if (!$order->store()) {
                JError::raiseError(500, $row->getError() );
            }
            $mainframe->redirect(JURI::root().'index.php?option=com_bookpro&view=mypage');
            return;

        }
        function login(){

            $mainframe=JFactory::getApplication('site');
            $return = JRequest::getVar('return', '', 'method', 'base64');
            $return = base64_decode($return);
            $options = array();
            $options['remember'] = JRequest::getBool('remember', false);
            $options['return'] = $return;
            $credentials = array();
            $credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
            $credentials['password'] = JRequest::getString('passwd', '', 'post', JREQUEST_ALLOWRAW);
            //preform the login action
            $error = $mainframe->login($credentials, $options);
            echo $error;
            die();
        }

        public function bplogin()
        {
            //JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

            $mainframe=JFactory::getApplication('site');
            $return = JRequest::getVar('return', '', 'method', 'base64');
            $return1 = JRequest::getVar('return', '', 'method', 'base64');
            $return = base64_decode($return);
            $options = array();
            $options['remember'] = JRequest::getBool('remember', false);
            $options['return'] = $return;
            $credentials = array();
            $credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
            $credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
            $result= $mainframe->login($credentials, $options);

            if ($result) {
                // Success
                $config = &AFactory::getConfig();
                $user = JFactory::getUser();

                $acontroller = new AController();
                $groupUser = $acontroller->checkGroupForUser();

                if($config->supplierUsergroup == $groupUser){
                    $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=supplierpage&Itemid='.JRequest::getVar('Itemid'));
                }else{
                    $mainframe->redirect($return,false);
                }
            }else{

                $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=login&return='.$return1);
            }
        }


        function checkusername(){
            $arg_params = &JComponentHelper::getParams( 'com_bookpro' );
            $username = JRequest::getVar('username', '', 'post', 'username');
            if($username)
            {
                // check if already used
                $db	   =& JFactory::getDBO();
                $query = "SELECT id FROM #__users WHERE `username`='".$username."' LIMIT 1";
                $db->setQuery( $query );
                $usralreadyexist = $db->loadResult();
                // check if blocked
                $notAccepted = 0;

                if( $usralreadyexist )
                {
                    // already in use
                    echo '<span class="invalid">'.JText::_( 'BOOKPRO_CUSTOMER_USERNAME_INVALID' ).'</span>';
                }
                elseif ( $notAccepted )
                {
                    // username blocked
                    echo '<span class="invalid">'.JText::_( 'BOOKPRO_CUSTOMER_USERNAME_INVALID' ).'</span>';
                }
                else
                {
                    echo JText::_("BOOKPRO_CUSTOMER_USERNAME_VALID");
                }
            }
            die;
        }
        function checkemail(){
            $input=JFactory::getApplication()->input;
            $email=$input->get('email','','email');
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('u.id');
            $query->from('#__users AS u');
            $query->where('u.email='.$db->quote($email));
            $db->setQuery($query);
            $result=$db->loadResult();

            echo $result;
            exit();
        }
        function register()

        {
        	
            //JSession::checkToken() or die( JText::_( 'Invalid Token' ) );
            $config=AFactory::getConfig();
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $user = JFactory::getUser();
            /* @var $user JUser */
            $config = AFactory::getConfig();

            $params=JComponentHelper::getParams('com_users');
            $useractivation = $params->get('useractivation');

            //JFactory::getLanguage()->load('com_users');

            $post = JRequest::get('post');
            if ($user->id) {
                $this->_model->setIdByUserId();
                $post['id'] = $this->_model->getId();
            } else
                $post['id'] = 0;

            
            
            $post['name'] = $post['firstname'].' '.$post['lastname'];
            
            $isNew = $post['id'] == 0;
            if (($useractivation == 1) || ($useractivation == 2)){
                $post['activation']=JApplication::getHash(JUserHelper::genRandomPassword());
                $post['block']=1;
            }
            
            $id = $this->_model->store($post);
            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('COM_BOOKPRO_REGISTER_SUCCESS'), 'message');
            }else {
                $mainframe->enqueueMessage(JText::_('COM_BOOKPRO_REGISTER_ERROR'), 'error');
                $this->setRedirect(JURI::base());
                exit;
            }
            //handle email notification
            if($useractivation == 1){

                $config = JFactory::getConfig();

                $post['fromname'] = $config->get('fromname');
                $post['mailfrom'] = $config->get('mailfrom');
                $post['sitename'] = $config->get('sitename');
                $post['siteurl'] = JUri::root();

                //$uri = JUri::getInstance();
                //$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));

                $post['activate'] = JUri::base().'index.php?option=com_users&task=registration.activate&token=' . $post['activation'];

                $emailSubject = JText::sprintf(
                    'COM_BOOKPRO_EMAIL_ACCOUNT_DETAILS',
                    $post['name'],
                    JUri::base()
                );

                $emailBody = JText::sprintf('COM_BOOKPRO_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
                    $post['name'],
                    $post['sitename'],
                    $post['activate'],
                    JUri::base(),
                    $post['username']
                );
                $return = JFactory::getMailer()->sendMail($post['mailfrom'], $post['fromname'], $post['email'], $emailSubject, $emailBody);

            }
            //redirect to complete view
            if (($useractivation == 1) || ($useractivation == 2)){

                $this->setMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
                $this->setRedirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
                return;

            }
            //

            AImporter::helper('email');
            $mailer=new EmailHelper();
            if($config->sendRegistrationsEmails>0){
                $mailer->registerNotify($id);
            }
            if($config->autoLogin){
                $options = array();
                $options['remember'] = JRequest::getBool('remember', true);
                $credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
                $credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
                $error = $mainframe->login($credentials, $options);
            }
            $return=JRequest::getVar('return');
            if($return){
                $this->setRedirect(base64_decode($return));
            }else {
                $config = &AFactory::getConfig();

                $acontroller = new AController();
                $groupUser = $acontroller->checkGroupForUser();

                if($config->supplierUsergroup == $groupUser){

                    $mainframe->redirect(JURI::base().'index.php?option=com_bookpro&view=supplierpage&Itemid='.JRequest::getVar('Itemid'));
                }else{
                    $mainframe->redirect(JURI::base().'index.php');
                }
            }
            exit;
        }

        function getstates(){
            $country_id = JRequest::getInt('country_id',0);
            AImporter::model('states');
            $model = new BookProModelStates();
            $lists=array('order'=>'id','order_Dir' => 'ASC','country_id'=>$country_id);
            $model->init($lists);
            $fullList = $model->getData();
            echo  AHtmlFrontEnd::getFilterSelect('states', JText::_('COM_BOOKPRO_SELECT_STATE'), $fullList, $select, false, 'onchange="changestate(this)"', 'id', 'state_name');
            die;
        }
        function getcity()
        {
            $country_id = JRequest::getInt('country_id',0);
            AImporter::model('airports');
            $model = new BookProModelAirports();
            $lists=array('order'=>'id','order_Dir' => 'ASC','country_id'=>$country_id);
            $model->init($lists);
            $fullList = $model->getData();
            echo AHtmlFrontEnd::getFilterSelect('city', JText::_('COM_BOOKPRO_SELECT_CITY'), $fullList, $select, false, 'id="city"', 'id', 'title');
            return;
        }

}
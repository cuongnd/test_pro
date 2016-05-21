<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 * @since       1.6
 */
class WebsiteControllerWebsite extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// An website edit form can come from the websites or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if ($this->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'website&return=featured';
		}
	}
    public function getListStep()
    {
        $model_website=$this->getModel();
        $steps=$model_website->getListStep();
        return $steps;
    }
    function nextStep($currentStep='')
    {
        $input=JFactory::getApplication()->input;
        $currentStep=$currentStep?$currentStep:$input->getString('currentStep','formBase');
        $nextStep='';
        $steps=$this->getListStep();
        $currentKey=0;
        foreach($steps as $key=>$step)
        {

            if(strtolower($step)==strtolower($currentStep))
            {
                $currentKey=$key;
                break;
            }
        }
        $nextStep=$steps[$currentKey+1];
        call_user_func_array(array('WebsiteControllerWebsite', $nextStep), array());

    }

    //save session website
    function next()
    {
        $input=JFactory::getApplication()->input;
        $formName=$input->getString('formName','formBase');
        $post=$input->getArray($_POST);
        $your_domain=strtolower($post['your_domain']);
        $sub_domain=strtolower($post['sub_domain']);
        $action=$input->getString('action','');
        $domain_id=$post['domain_id'];
        $email=strtolower($post['email']);
        $session_website = JModelLegacy::getInstance('session_website');
        $session_website->load();
        $session_website->sub_domain=strtolower($sub_domain);
        $session_website->your_domain=strtolower($your_domain);
        $session_website->email=$email;
        $session_website->saveToSession();
        $domain=websiteHelperFrontEnd::check_domain_enable_create_website($domain_id);
        if(!$domain)
        {
            $this->setRedirect('index.php?option=com_website&view=website','you cannot create website this domain');
        }
        $sub_domain="$sub_domain.$domain->domain";
        if($sub_domain!='')
        {
            $session=JFactory::getSession();
            $session->set('sub_domain',$sub_domain);
            $session->set('your_domain',$your_domain);
            $session->set('email',$email);
        }
        $this->setRedirect('index.php?option=com_website&view=website&layout=main&firstsetup=1&action='.$action);
        return;
    }
    function SetProgressSuccess($function,&$respone_array=array())
    {
        $steps=$this->getListStep();
        $currentKey=0;
        foreach($steps as $key=>$step)
        {

            if(strtolower($step)==strtolower($function))
            {
                $currentKey=$key;
                break;
            }
        }
        $success=(100/count($steps)+1)*$currentKey;
        $respone_array[] = array(
            'type'=>'input',
            'key' => 'input[name="progress_success"]',
            'contents' => (string)$success
        );
    }
    protected function createBasicInfoWebsite()
    {
        $session=JFactory::getSession();
        $sub_domain=$session->get('sub_domain','');
        $modelWebsite=$this->getModel();
        $website_id=$modelWebsite->createBasicInfoWebsite($sub_domain);
        $session->set('website_id',$website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createbasicinfowebsite';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function insertDomainToWebsite()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $sub_domain=$session->get('sub_domain','');
        $your_domain=$session->get('your_domain','');
        $modelWebsite=$this->getModel();
        if(!$website_id)
        {
            $modelWebsite->setError(JText::_('there is no website to setup'));
        }
        if($website_id&&$sub_domain)
            $modelWebsite->insertDomainToWebsite($sub_domain,$website_id);
        if($website_id&&$your_domain)
            $modelWebsite->insertDomainToWebsite($your_domain,$website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='insertdomaintowebsite';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createConfiguration()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $sub_domain=$session->get('sub_domain','');
        $your_domain=$session->get('your_domain','');
        $modelWebsite=$this->getModel();
        if(!$website_id)
        {
            $modelWebsite->setError(JText::_('there is no website to setup'));
        }
        if($website_id&&$sub_domain)
            $modelWebsite->createConfiguration($website_id);
        if($website_id&&$your_domain)
            $modelWebsite->insertDomainToWebsite($your_domain,$website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createconfiguration';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createGroupUser()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createGroupUser($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();

        $layout='creategroupuser';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createControl()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createControl($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();

        $layout='createcontrol';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createViewAccessLevels()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createViewAccessLevels($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createviewaccesslevels';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createSupperAdmin()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createSupperAdmin($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createsupperadmin';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createPlugins()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createPlugins($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createPlugins';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createComponents()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createComponents($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createComponents';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createModules()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createModules($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createmodules';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createStyles()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createstyles($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createstyles';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createMenus()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createMenus($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createmenus';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function changeParams()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->changeParams($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='changeparams';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    protected function createContentCategory()
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $modelWebsite=$this->getModel();
        $modelWebsite->createContentCategory($website_id);
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->errors=$modelWebsite->getErrors();
        $layout='createcontentcategory';
        if(count($view->errors))
            $layout=$modelWebsite->getPrevLayoutByLayout($layout);
        $view->setLayout($layout);
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess($layout,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    function checkExistsDomain()
    {
        $input=JFactory::getApplication()->input;
        $modelWebsie=$this->getModel();
        $domain=$input->getString('your_domain','');
        $user=JFactory::getUser();
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $exists=$modelWebsie->checkExistsDomain($domain);
        $view->websiteExists=$exists;
        if($exists)
        {
            $view->setupCompleted=$modelWebsie->checkStateSetupWebsite($domain);
            if(!$view->setupCompleted)
                $modelWebsie->sendEmailAlertSetupWebsite('nguyendinhcuong@gmail.com');
        }
        $exists=$exists?'true':'false';
        $view->setLayout('suggestionyourdomain');
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array['exists']=$exists;
        $respone_array['html'][] = array(
            'key' => '.suggestionyourdomain',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }
    function checkExistsSubDomain()
    {
        $input=JFactory::getApplication()->input;
        $modelWebsie=$this->getModel();
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $domain=$input->getString('sub_domain','');
        $domain_id=$input->getInt('domain_id',0);
        if($domain_id==0)
        {
            $respone_array['html'][] = array(
                'key' => '.suggestionsubdomain',
                'contents' => 'please select domain website'
            );
            echo json_encode($respone_array);
            die;
        }
        $exists=$modelWebsie->checkExistsDomain($domain);
        $view->websiteExists=$exists;
        if($exists)
        {
            $view->setupCompleted=$modelWebsie->checkStateSetupWebsite($domain);
            if(!$view->setupCompleted)
                $modelWebsie->sendEmailAlertSetupWebsite('nguyendinhcuong@gmail.com');
        }
        $exists=$exists?'true':'false';
        $view->setLayout('suggestionsubdomain');
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array['exists']=$exists;
        $respone_array['html'][] = array(
            'key' => '.suggestionsubdomain',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }
    protected function finish()
    {
        $view = &$this->getView('website', 'html', 'WebsiteView');
        $view->setLayout('finish');
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.main-display',
            'contents' => $contents
        );
        $this->SetProgressSuccess(__FUNCTION__,$respone_array);
        echo json_encode($respone_array);
        exit();
    }
    //ajax check exists your domain and sub domain
    function ajaxCheckExistsYourDomainAndSubDomain()
    {
        $stop=false;
        $input=JFactory::getApplication()->input;
        $youDomain=$input->getString('your_domain','');
        $modelWebsite=$this->getModel();
        if($youDomain!=''&&$modelWebsite->checkExistsDomain($youDomain))
            $stop=true;
        $sub_domain=$input->getString('sub_domain','');


        if($sub_domain!=''&&$modelWebsite->checkExistsDomain($sub_domain))
            $stop=true;
        $arrayResult=array();
        $arrayResult['stop']=$stop;
        echo json_encode($arrayResult);
        die;
    }
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_website.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}
    public function ajaxLoadPropertiesWebsite()
    {
        $modelWebsite=$this->getModel();
        $website=JFactory::getWebsite();
        $modelWebsite->setState('website.id',$website->website_id);
        $form=$modelWebsite->getForm();
        $fieldSet = $form->getFieldset();
        foreach ($fieldSet as $field)
        {
            $html[] = $field->renderField(array(),true);
        }
        $contents='<div class="properties website">
	                    <div class="form-horizontal">
		                    '.implode('', $html).'
                        </div>
                    </div>';
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.block-properties .panel-body',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;

    }
    public function ajaxSavePropertiesWebsite()
    {
        $websiteTable=JTable::getInstance('Website','JTable');
        $website=JFactory::getWebsite();
        $app=JFactory::getApplication();
        $form=$app->input->get('jform',array(),'array');
        $params=$form['params'];
        $form['params']=json_encode($params);

        $form['id']=$website->website_id;
        $websiteTable->load( $form['id']);
        $websiteTable->bind($form);
        if(!$websiteTable->store())
        {
            echo $websiteTable->getError();
        }else{
            echo 1;
        }
        die;
    }
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_website.website.' . $recordId))
		{
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', 'com_website.website.' . $recordId))
		{
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId)
			{
				return true;
			}
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   1.6
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('website', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_website&view=websites' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model  The data model object.
	 * @param   array         $validData   The validated data.
	 *
	 * @return	void
	 *
	 * @since	3.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{

		return;
	}
}

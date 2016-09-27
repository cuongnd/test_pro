<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Module controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 * @since       1.6
 */
class ModulesControllerModule extends JControllerForm
{
	/**
	 * Override parent add method.
	 *
	 * @return  mixed  True if the record can be added, a JError object if not.
	 *
	 * @since   1.6
	 */
	public function add()
	{

		$app = JFactory::getApplication();

		// Get the result of the parent method. If an error, just return it.
		$result = parent::add();
		if ($result instanceof Exception) {
			return $result;
		}

		// Look for the Extension ID.
		$extensionId = $app->input->get('eid', 0, 'int');
		if (empty($extensionId)) {
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . '&layout=edit', false));
			return JError::raiseWarning(500, JText::_('COM_MODULES_ERROR_INVALID_EXTENSION'));
		}

		$app->setUserState('com_modules.add.module.id', $extensionId);
		$app->setUserState('com_modules.add.module.params', null);

		// Parameters could be coming in for a new item, so let's set them.
		$params = $app->input->get('params', array(), 'array');
		$app->setUserState('com_modules.add.module.params', $params);
	}
	public function ajax_get_render_module(){
		$app=JFactory::getApplication();
		echo "sdfsdfsd";
		die;
	}
	public function ajax_save_field_params()
	{
		$app=JFactory::getApplication();
		$website=JFactory::getWebsite();
		$fields=$app->input->get('fields','','string');
		$element_path=$app->input->get('element_path','','string');
		$db=JFactory::getDbo();
		require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
		require_once JPATH_ROOT.'/components/com_modules/helpers/module.php';
		$table_control=new JTableUpdateTable($db,'control');
		$filter=array(
			'element_path'=>$element_path,
			'type'=>module_helper::ELEMENT_TYPE
		);
		if($element_path==module_helper::MODULE_ROOT_NAME)
		{
		}else{
			$filter['website_id']=$website->website_id;
		}
		$table_control->load($filter);
		if(!$table_control->id &&$element_path!=module_helper::MODULE_ROOT_NAME )
		{
			throw new Exception('cannot found control, this control must created before setup config, please check again');
		}
		if(!$table_control->id &&$element_path==module_helper::MODULE_ROOT_NAME )
		{
            throw new Exception('there are no global module config in database, please config global module property in backend ad layout first');
		}
		if($element_path==module_helper::MODULE_ROOT_NAME)
		{
			$table_control->website_id=null;
		}else{
			$table_control->website_id=$website->website_id;
		}

		$table_control->element_path=$element_path;
		$table_control->type=module_helper::ELEMENT_TYPE;
		$table_control->fields=$fields;
		$response=new stdClass();
		$response->e=0;
		if(!$table_control->store())
		{
			$response->e=1;
			$response->r=$table_control->getError();
		}else{
			$response->r="save success";
		}
		echo json_encode($response);
		die;
	}


	public function AjaxRemoveModule()
	{
		$app=JFactory::getApplication();
		$module_id=$app->input->get('module_id',0,'int');
		$tableModule=JTable::getInstance('Module','JTable');
		$tableModule->load($module_id);
		$tableModule->delete();
		die;
	}
	public  function aJaxAddPositionInScreen()
	{
		$app=JFactory::getApplication();
		$post=$app->input->getArray($_POST);
		$screenSize=$post['screenSize'];
		$position=$post['position'];
		$positionSetting['screensize']=$screenSize;
		JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
		$tablePosition=JTable::getInstance('position','JTable');
		$website=JFactory::getWebsite();
		$positionSetting['alias']=$positionSetting['position'];
		$positionSetting['website_id']=$website->website_id;
		$tablePosition->bind($positionSetting);
		if(!$tablePosition->store())
		{
			echo $tablePosition->getError();
			die;
		}
		die;
	}
	public  function aJaxSaveContent()
	{
		$app=JFactory::getApplication();
		$module_id=$app->input->get('module_id',0,'int');
		$content=$app->input->get('content','','string');
		$content=base64_decode($content);
		$tableModule=JTable::getInstance('Module','JTable');
		$tableModule->load($module_id);
		$tableModule->content=$content;
		if(!$tableModule->store())
		{
			echo $tableModule->getError();
		}
		die;
	}
	public  function aJaxInsertModule()
	{
		$app=JFactory::getApplication();
		$modelModule= $this->getModel();
		$module_id=$app->input->get('module_id',0,'int');
		$position=$app->input->get('position','','string');
		$tableExtension=JTable::getInstance('Extension','JTable');
		$tableExtension->load($module_id);
		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
		$screenSize=UtilityHelper::get_current_screen_size_id_editing();
		$tableModule=JTable::getInstance('Module','JTable');
		$tableModule->id=0;
        require_once JPATH_ROOT.'/libraries/joomla/user/helper.php';
		$tableModule->title=$tableExtension->name.JUserHelper::genRandomPassword();
		$tableModule->module=$tableExtension->element;
		$tableModule->showtitle=1;
		$tableModule->position='position-'.$position;
		$tableModule->screensize=$screenSize;
		$tableModule->published=1;
		$tableModule->params=$tableExtension->params;
		$tableModule->extension_id=$module_id;
		$tableModule->position_id=$position;
		$tableModule->client_id=0;
		$tableModule->access=1;
        if(!$tableModule->store())
        {
            echo $tableModule->getErrorMsg();
            die;
        }

		$newModuleId=$tableModule->id;

		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		//	 * $query->insert('#__a')->set('id = 1');

		$query->insert('#__modules_menu')->set('moduleid='.(int)$newModuleId.',menuid=NULL');
		$db->setQuery($query);
		if(!$db->execute())
		{
			echo $db->getErrorMsg();
			die;
		}

		$module = &JModuleHelper::getModuleById($newModuleId);
		require_once JPATH_ROOT.'/libraries/joomla/document/html/renderer/module.php';
		$renderModule=new JDocumentRendererModule(JFactory::getDocument());
		$contents=$renderModule->render($module);
		//$contents = JModuleHelper::renderModule($module);
		ob_end_clean();
		$respone_bject=new stdClass();
		$respone_bject->modulecontent=$contents;
		echo json_encode($respone_bject);
		die;
	}
	public function ajaxSavePropertyBlock()
	{
		echo "hello";
	}
	public function ajaxLoadFieldTypeOfModule()
	{
		$app=JFactory::getApplication();
		$module_id=$app->input->get('module_id',0,'int');
		$field=$app->input->get('field','','string');
		$modelModule= $this->getModel();
		$modelModule->setState('module.id', $module_id);
		$form=$modelModule->getForm();
		ob_start();
		$respone_array=array();
		$contents = $form->getInput($field);

		ob_end_clean(); // get the callback function
		$respone_array[] = array(
			'key' => '.itemField .panel-body',
			'contents' => $contents
		);
		echo json_encode($respone_array);
		die;
	}
	public function ajaxSavePropertiesModule()
	{
		$app = JFactory::getApplication();
		$post = file_get_contents('php://input');
		$post = json_decode($post);
		$form=(array)$post->jform;
		$tableModule=JTable::getInstance('Module','JTable');

		$tableModule->load($form['id']);
		$tableModule->bind($form);
		$tableModule->params=json_encode($form['params']);

		$response=new stdClass();
		$response->e=0;
		$response->r="save success";
		if(!$tableModule->store())
		{
			$response->e=1;
			$response->r=$tableModule->getError();
		}
		echo json_encode($response);
		die;
	}
	public function ajaxSavePropertyModule()
	{

		$app=JFactory::getApplication();
		$form=$app->input->get('jform',array(),'array');

		$module_id=$app->input->get('module_id',0,'int');
		$tableModule=JTable::getInstance('Module','JTable');
		$tableModule->load($module_id);

		$tableModule->bind($form);
		$result = new stdClass();
		$result->e=0;
		$result->m=JText::_('copy success');
		if(!$tableModule->store())
		{
			$result->e=1;
			$result->m=$tableModule->getError();
		}
		echo json_encode($result);
		die;
	}
	public  function ajaxLoadPropertiesModule()
	{


		$app=JFactory::getApplication();
		$module_id=$app->input->get('module_id',0,'int');
		$app->input->set('id',$module_id);
		$modelModule= $this->getModel();
		$modelModule->setState('module.id', $module_id);
		$form=$modelModule->getForm();
		$options=$form->getFieldsets();
		ob_start();
		?>
		<div class="properties module">
			<div class="panel-group" id="accordion<?php echo $module_id ?>" role="tablist" aria-multiselectable="true">
				<?php
				foreach($options as $key=>$option)
				{
					$fieldSet = $form->getFieldset($key);
					?>

					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?php echo $key ?>">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion<?php echo $module_id ?>" href="#collapse<?php echo $key ?>" aria-expanded="true" aria-controls="collapse<?php echo $key ?>">
									<?php echo $option->label ?>
								</a>
							</h4>
						</div>
						<div id="collapse<?php echo $key ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $key ?>">
							<div class="panel-body">
								<?php
								foreach ($fieldSet as $field)
								{
									?>
									<div class="form-horizontal">
										<?php echo $field->renderField(array(),true); ?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>
		<?php
		$contents=ob_get_clean();
		$doc=JFactory::getDocument();
		$respone_array[] = array(
			'key' => '.block-properties .panel-body',
			'contents' => $contents
		);
		echo json_encode($respone_array);
		die;
	}
	/**
	 * Proxy for getModel
	 * @since   1.6
	 */
	public function getModel($name = 'module', $prefix = 'ModulesModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
	public function aJaxGetOptionModule()
	{
		$modelModule= $this->getModel();
		$view = &$this->getView('module', 'html', 'ModulesView');
		$view->setModel($modelModule , true );
		ob_start();
		$view->setLayout('edit');
		$view->display();
		$respone_array=array();
		$contents = ob_get_contents();

		ob_end_clean(); // get the callback function
		$respone_array[] = array(
			'key' => '#dialog-body',
			'contents' => $contents
		);
		echo json_encode($respone_array);
		die;

	}
	/**
	 * Override parent cancel method to reset the add module state.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   1.6
	 */
	public function cancel($key = null)
	{
		$app = JFactory::getApplication();

		$result = parent::cancel();

		$app->setUserState('com_modules.add.module.id', null);
		$app->setUserState('com_modules.add.module.params', null);

		return $result;
	}

	/**
	 * Override parent allowSave method.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowSave($data, $key = 'id')
	{
		// use custom position if selected
		if (isset($data['custom_position']))
		{
			if (empty($data['position']))
			{
				$data['position'] = $data['custom_position'];
			}

			unset($data['custom_position']);
		}

		return parent::allowSave($data, $key);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   3.2
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_modules.module.' . $recordId))
		{
			return true;
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   string  $model  The model
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.7
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('Module', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_modules&view=modules'.$this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$app = JFactory::getApplication();
		$task = $this->getTask();

		switch ($task)
		{
			case 'save2new':
				$app->setUserState('com_modules.add.module.id', $model->getState('module.id'));
				break;

			default:
				$app->setUserState('com_modules.add.module.id', null);
				break;
		}

		$app->setUserState('com_modules.add.module.params', null);
	}
}

<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * The Menu Type Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusControllerMenu extends JControllerForm
{
	/**
	 * Dummy method to redirect back to standard controller
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menus', false));
	}
    public function ajax_create_new_menu_type(){
        $website=JFactory::getWebsite();
        $app      = JFactory::getApplication();
        $input=$app->input;
        $menu_type=$input->getString('menu_type_name','');
        require_once JPATH_ROOT . '/libraries/legacy/table/menu/type.php';
        $table_menu_type = JTable::getInstance('menutype', 'JTable');
        $table_menu_type->menutype=$menu_type;
        $table_menu_type->title=$menu_type;
        $table_menu_type->description=$menu_type;
        $table_menu_type->website_id=$website->website_id;
        $result = new stdClass();
        $result->e = 0;
        $ok = $table_menu_type->check();
        if (!$ok) {
            $result->e = 1;
            $result->m = $table_menu_type->getError();
            echo json_encode($result);
            die;
        }
        $ok = $table_menu_type->store();
        if (!$ok) {
            $result->e = 1;
            $result->m = $table_menu_type->getError();
            echo json_encode($result);
            die;
        }
        $table_menu_item = JTable::getInstance('Menu');
        $table_menu_item->id = 0;
        $table_menu_item->title = 'Menu_item_root';
        $table_menu_item->alias = 'root';
        $ok = $table_menu_item->check();
        if (!$ok) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
            echo json_encode($result);
            die;
        }
        if (!$table_menu_item->parent_store()) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
            echo json_encode($result);
            die;
        }
        $table_menu_item_menu_type = JTable::getInstance('menuitemmenutype');
        $table_menu_item_menu_type->id=0;
        $table_menu_item_menu_type->menu_type_id = $table_menu_type->id;
        $table_menu_item_menu_type->menu_id =  $table_menu_item->id;
        $ok = $table_menu_item_menu_type->store();
        if (!$ok) {
            $result->e = 1;
            $result->m = $table_menu_item_menu_type->getError();
            echo json_encode($result);
            die;
        }
        $result->m = "create new menu type successfully";
        echo json_encode($result);
        die;
    }
    public function ajax_delete_menu_type(){
        $result = new stdClass();
        $result->e = 0;
        $website=JFactory::getWebsite();
        $app      = JFactory::getApplication();
        $input=$app->input;
        $menu_type_id=$input->getInt('menu_type_id',0);


        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->delete_all('#__menu AS menu','menu.*')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
            ->where('menu_type_id_menu_id.menu_type_id='.(int)$menu_type_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            $result->e = 1;
            $result->m = $db->getErrorMsg();
            echo json_encode($result);
            die;
        }




        //delete all extension supper admin
        $query = $db->getQuery(true);
        $query->delete('ueb3c_menu_types')
            ->where('id='.(int)$menu_type_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            $result->e = 1;
            $result->m = $db->getErrorMsg();
            echo json_encode($result);
            die;
        }

        $result->m = "delete menu type successfully";
        echo json_encode($result);
        die;
    }
    public function ajax_update_menu_type_title(){
        $result = new stdClass();
        $result->e = 0;
        $website=JFactory::getWebsite();
        $app      = JFactory::getApplication();
        $input=$app->input;
        $menu_type_id=$input->getInt('menu_type_id',0);
        $menu_type_title=$input->getString('menu_type_title','');
        JTable::addIncludePath(JPATH_ROOT.'/libraries/legacy/table/menu');
        $table_menu_type=JTable::getInstance('MenuType','JTable');
        $table_menu_type->load($menu_type_id);
        $table_menu_type->title=$menu_type_title;
        if(!$table_menu_type->check())
        {
            $result->e = 1;
            $result->m =$table_menu_type->getError() ;
            echo json_encode($result);
            die;
        }
        if(!$table_menu_type->store())
        {
            $result->e = 1;
            $result->m =$table_menu_type->getError() ;
            echo json_encode($result);
            die;
        }

        $result->m = "edit  menu type title successfully";
        echo json_encode($result);
        die;
    }
	/**
	 * Method to save a menu item.
	 *
	 * @return  void
	 */

	public function save($key = null, $urlVar = null)
	{

		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app      = JFactory::getApplication();
		$data     = $this->input->post->get('jform', array(), 'array');
		$context  = 'com_menus.edit.menu';
		$task     = $this->getTask();
		$recordId = $this->input->getInt('id');

        $supperAdmin=JFactory::isSupperAdmin();
        if($supperAdmin)
        {

            if (!$data['website_id'])
            {
                $this->setMessage(JText::_('JLIB_DATABASE_ERROR_WEBSITE_EMPTY'), 'Error');
                $this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit&id='.$recordId, false));
                return false;
            }
        }
        else
        {
            $website=JFactory::getWebsite();
            $data['website_id']=$website->website_id;

        }

		// Make sure we are not trying to modify an administrator menu.
		if (isset($data['client_id']) && $data['client_id'] == 1)
		{
			JError::raiseNotice(0, JText::_('COM_MENUS_MENU_TYPE_NOT_ALLOWED'));

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));

			return false;
		}
        $data['id'] = $recordId;

		// Populate the row id from the session.


		// Get the model and attempt to validate the posted data.
		$model	= $this->getModel('Menu');
		$form	= $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		$data	= $model->validate($form, $data);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			// Save the data in the session.
			$app->setUserState('com_menus.edit.menu.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState('com_menus.edit.menu.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit&id='.$recordId, false));

			return false;
		}

		$this->setMessage(JText::_('COM_MENUS_MENU_SAVE_SUCCESS'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context.'.id');
				$this->holdEditId($context, $recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit'.$this->getRedirectToItemAppend($recordId), false));
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context.'.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context.'.data', null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menus', false));
				break;
		}
	}
}

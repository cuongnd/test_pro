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
 * The Menu Item Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusControllerItem extends JControllerForm
{
    /**
     * Method to add a new menu item.
     *
     * @return  mixed  True if the record can be added, a JError object if not.
     *
     * @since   1.6
     */
    public function add()
    {
        $app = JFactory::getApplication();
        $context = 'com_menus.edit.item';

        $result = parent::add();
        if ($result) {
            $app->setUserState($context . '.type', null);
            $app->setUserState($context . '.link', null);

            $menuType = $app->getUserStateFromRequest($this->context . '.filter.menutype', 'menutype', 'mainmenu', 'cmd');

            $this->setRedirect(JRoute::_('index.php?option=com_menus&view=item&menutype=' . $menuType . $this->getRedirectToItemAppend(), false));
        }

        return $result;
    }

    public function ajax_save_content_php_code()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $php_content = $input->get('php_content', '', 'string');
        $menu_item_id = $input->get('menu_item_id', 0, 'int');
        $table_menu_item = JTable::getInstance('Menu');
        $table_menu_item->load($menu_item_id);
        $table_menu_item->php_content = $php_content;
        $result = new stdClass();
        $result->e = 0;
        if (!$table_menu_item->store()) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
        } else {
            $result->m = "save content successfully";
        }
        echo json_encode($result);
        die;
    }

    /**
     * Method to run batch operations.
     *
     * @param   object $model The model.
     *
     * @return  boolean     True if successful, false otherwise and internal error is set.
     *
     * @since   1.6
     */
    public function batch($model = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $model = $this->getModel('Item', '', array());

        // Preset the redirect
        $this->setRedirect(JRoute::_('index.php?option=com_menus&view=items' . $this->getRedirectToListAppend(), false));

        return parent::batch($model);
    }

    public function ajax_get_list_icon()
    {
        $app = JFactory::getApplication();
        $keyword = $app->input->get('keyword', '', 'string');
        $db = JFactory::getDbo();
        $list_icon = JUtility::get_class_icon_font();
        $list_result = array();
        foreach ($list_icon as $icon) {
            if (strpos($icon, $keyword)) {
                $item = new stdClass();
                $item->id = $icon;
                $item->text = $icon;
                $list_result[] = $item;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($list_result, JSON_NUMERIC_CHECK);
        die;

    }

    /**
     * @throws Exception
     */
    public function ajax_update_item()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $data = $input->get('data', array(), 'array');
        $menu_type_id = $data['menu_type_id'];
        $db = JFactory::getDbo();
        $list_ordering = $input->get('list_ordering', array(), 'array');
        $result = new stdClass();
        $result->e = 0;
        $result->m = "save success";
        if (count($list_ordering)) {

            foreach ($list_ordering as $ordering => $id) {
                $query = $db->getQuery(true);
                $query->update('#__menu')
                    ->set('ordering=' . (int)$ordering)
                    ->where('id=' . (int)$id);
                $db->setQuery($query);
                if (!$db->execute()) {
                    $result->e = 1;
                    $result->m = $db->getErrorMsg();
                    echo json_encode($result);
                    die;
                }
            }
        }


        $table_menu_item = JTable::getInstance('Menu','JTable');
        $table_menu_item->load($data['id']);
        if ($table_menu_item->home) {
            $data['language'] = '*';
            $data['published'] = 1;
        }
        if (!$table_menu_item->bind($data, '', 1)) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
            echo json_encode($result);
            die;
        }

        if (!$table_menu_item->store()) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
            echo json_encode($result);
            die;
        }

        echo json_encode($result);
        die;
    }

    public function ajax_clone_item_menu()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id', 0, 'int');
        $table_menu_item = JTable::getInstance('Menu');
        $table_menu_item->load($id);
        $table_menu_item->id = 0;
        $table_menu_item->lft = null;
        $table_menu_item->rgt = null;
        $table_menu_item->level = null;
        $table_menu_item->title = $table_menu_item->title . '1';
        $table_menu_item->alias = $table_menu_item->alias . JUserHelper::genRandomPassword(3);
        $result = new stdClass();
        $result->e = 0;
        if (!$table_menu_item->store('clone')) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
        } else {
            $result->m = "clone successfully";
            $menu_clone=new stdClass();
            $menu_clone->id=$table_menu_item->id;
            $menu_clone->parent_id=$table_menu_item->parent_id;
            $menu_clone->title=$table_menu_item->title;
            $result->r = $menu_clone;
        }
        echo json_encode($result);
        die;
    }
    public function ajax_remove_item_menu()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id', 0, 'int');
        $table_menu_item = JTable::getInstance('Menu');

        //
        $result = new stdClass();
        $result->e = 0;
        if (!$table_menu_item->delete($id)) {
            $result->e = 1;
            $result->m = $table_menu_item->getError();
        } else {
            $result->m = "delete successfully";
        }
        echo json_encode($result);
        die;
    }

    public function ajax_save_field_params()
    {
        $app = JFactory::getApplication();
        $menu_params_field = $app->input->get('fields', '', 'string');
        $db = JFactory::getDbo();
        $website = JFactory::getWebsite();
        require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
        $table_website = new JTableUpdateTable($db, 'website');
        $table_website->load($website->website_id);
        $table_website->menu_params_field = $menu_params_field;
        $response = new stdClass();
        $response->e = 0;
        if (!$table_website->store()) {
            $response->e = 1;
            $response->r = $table_control->getError();
        } else {
            $response->r = "save success";
        }
        echo json_encode($response);
        die;
    }


    public function ajax_add_sub_item_menu()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id', 0, 'int');
        $table_menu_item = JTable::getInstance('Menu');
        $table_menu_item->load($id);
        $table_menu_item->id = 0;
        $table_menu_item->setLocation($table_menu_item->parent_id, 'last-child');
        $table_menu_item->parent_id = $id;
        $table_menu_item->lft = null;
        $table_menu_item->rgt = null;
        $table_menu_item->level = null;
        $table_menu_item->title = $table_menu_item->title . '1';
        $table_menu_item->alias = $table_menu_item->alias . '1';

        $response = new stdClass();
        $response->e = 0;
        if (!$table_menu_item->store()) {
            $response->e = 1;

            $response->r = $table_menu_item->getError();
        } else {
            $response->r = "add menu successfully";
        }
        echo json_encode($response);
        die;
    }

    public function ajaxLoadFieldTypeOfComponent()
    {

        $app = JFactory::getApplication();
        $menu_id = $app->input->get('menu_id', 0, 'int');
        $field = $app->input->get('field', '', 'string');
        $modelPosition = $this->getModel();
        $modelPosition->setState('item.id', $menu_id);
        $app->input->set('id', $menu_id);
        $form = $modelPosition->getForm();
        ob_start();
        $respone_array = array();
        $contents = $form->getInput($field);

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.itemField .panel-body',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;
    }

    public function ajaxSavePropertyComponent()
    {
        $app = JFactory::getApplication();
        $form = $app->input->get('jform', array(), 'array');

        $menu_id = $app->input->get('menu_id', 0, 'int');
        JTable::addIncludePath(JPATH_ROOT . '/components/com_menus/tables');
        $tableMenuItem = JTable::getInstance('menu', 'JTable');
        $tableMenuItem->load($menu_id);
        $params = new JRegistry;
        $params->loadString($tableMenuItem->params);

        foreach ($form['params'] as $keyParam => $valueParam) {
            $params->set($keyParam, trim($valueParam));
        }
        $form['params'] = json_encode($params);


        $tableMenuItem->bind($form);
        if (!$tableMenuItem->store()) {
            echo $tableMenuItem->getError();
        }

        die;
    }


    public function aJaxGetOptionsMenuItem()
    {

        $app = JFactory::getApplication();
        $menu_type_id = $app->input->getInt('menu_type_id', 0);
        $currentLink = $app->input->getString('currentLink', '');
        $type = $app->input->getString('type', '');
        $clearLink = false;
        if ($type == 'component') {
            $uri = JUri::getInstance($currentLink);
            $component = $uri->getVar('option');
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('COUNT(*) AS total')
                ->from('#__components AS com')
                ->leftJoin('#__menu_types AS mt ON mt.website_id=com.website_id')
                ->where('mt.id=' . (int)$menu_type_id)
                ->where('com.element=' . $query->quote($component))
                ->where('com.enabled=1');
            $db->setQuery($query);
            $result = $db->loadResult();
            if (!$result) {
                $clearLink = true;
            }

        }
        $modelMenuItem = JModelLegacy::getInstance('Item', 'MenusModel');

        $form = $modelMenuItem->getForm();
        $form->setValue('menu_type_id', '', $menu_type_id);
        $response = array(
            'clearLink' => $clearLink ? 1 : 0,
            'parent_id' => $form->getInput('parent_id')
        );
        echo json_encode($response);
        die;
    }

    /**
     * Method to cancel an edit.
     *
     * @param   string $key The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     *
     * @since   1.6
     */
    public function cancel($key = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $context = 'com_menus.edit.item';
        $result = parent::cancel();

        if ($result) {
            // Clear the ancillary data from the session.
            $app->setUserState($context . '.type', null);
            $app->setUserState($context . '.link', null);
        }

        return $result;
    }

    /**
     * Method to edit an existing record.
     *
     * @param   string $key The name of the primary key of the URL variable.
     * @param   string $urlVar The name of the URL variable if different from the primary key
     * (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if access level check and checkout passes, false otherwise.
     *
     * @since   1.6
     */
    public function edit($key = null, $urlVar = null)
    {
        $app = JFactory::getApplication();
        $result = parent::edit();

        if ($result) {
            // Push the new ancillary data into the session.
            $app->setUserState('com_menus.edit.item.type', null);
            $app->setUserState('com_menus.edit.item.link', null);
        }

        return $result;
    }

    /**
     * Method to save a record.
     *
     * @param   string $key The name of the primary key of the URL variable.
     * @param   string $urlVar The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   1.6
     */
    public function save($key = null, $urlVar = null)
    {

        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $model = $this->getModel('Item', '', array());
        $data = $this->input->post->get('jform', array(), 'array');
        $task = $this->getTask();
        $context = 'com_menus.edit.item';
        $recordId = $this->input->getInt('id');

        // Populate the row id from the session.
        $data['id'] = $recordId;

        // The save2copy task needs to be handled slightly differently.
        if ($task == 'save2copy') {
            // Check-in the original row.
            if ($model->checkin($data['id']) === false) {
                // Check-in failed, go back to the item and display a notice.
                $this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'warning');
                return false;
            }

            // Reset the ID and then treat the request as for Apply.
            $data['id'] = 0;
            $data['associations'] = array();
            $task = 'apply';
        }

        // Validate the posted data.
        // This post is made up of two forms, one for the item and one for params.
        $form = $model->getForm($data);

        if (!$form) {
            JError::raiseError(500, $model->getError());

            return false;
        }

        if ($data['type'] == 'url') {
            $data['link'] = str_replace(array('"', '>', '<'), '', $data['link']);

            if (strstr($data['link'], ':')) {
                $segments = explode(':', $data['link']);
                $protocol = strtolower($segments[0]);
                $scheme = array('http', 'https', 'ftp', 'ftps', 'gopher', 'mailto', 'news', 'prospero', 'telnet', 'rlogin', 'tn3270', 'wais', 'url',
                    'mid', 'cid', 'nntp', 'tel', 'urn', 'ldap', 'file', 'fax', 'modem', 'git');

                if (!in_array($protocol, $scheme)) {
                    $app->enqueueMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'warning');
                    $this->setRedirect(
                        JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false)
                    );

                    return false;
                }
            }
        }

        $data = $model->validate($form, $data);

        // Check for the special 'request' entry.
        if ($data['type'] == 'component' && isset($data['request']) && is_array($data['request']) && !empty($data['request'])) {
            $removeArgs = array();

            // Preprocess request fields to ensure that we remove not set or empty request params
            $request = $form->getGroup('request');

            if (!empty($request)) {
                foreach ($request as $field) {
                    $fieldName = $field->getAttribute('name');

                    if (!isset($data['request'][$fieldName]) || $data['request'][$fieldName] == '') {
                        $removeArgs[$fieldName] = '';
                    }
                }
            }

            // Parse the submitted link arguments.
            $args = array();
            parse_str(parse_url($data['link'], PHP_URL_QUERY), $args);

            // Merge in the user supplied request arguments.
            $args = array_merge($args, $data['request']);

            // Remove the unused request params
            if (!empty($args) && !empty($removeArgs)) {
                $args = array_diff_key($args, $removeArgs);
            }

            $data['link'] = 'index.php?' . urldecode(http_build_query($args, '', '&'));
            unset($data['request']);
        }

        // Check for validation errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_menus.edit.item.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));

            return false;
        }

        // Attempt to save the data.
        if (!$model->save($data)) {
            // Save the data in the session.
            $app->setUserState('com_menus.edit.item.data', $data);

            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));

            return false;
        }

        // Save succeeded, check-in the row.
        if ($model->checkin($data['id']) === false) {
            // Check-in failed, go back to the row and display a notice.
            $this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));

            return false;
        }

        $this->setMessage(JText::_('COM_MENUS_SAVE_SUCCESS'));

        // Redirect the user and adjust session state based on the chosen task.
        switch ($task) {
            case 'apply':
                // Set the row data in the session.
                $recordId = $model->getState($this->context . '.id');
                $this->holdEditId($context, $recordId);
                $app->setUserState('com_menus.edit.item.data', null);
                $app->setUserState('com_menus.edit.item.type', null);
                $app->setUserState('com_menus.edit.item.link', null);

                // Redirect back to the edit screen.
                $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
                break;

            case 'save2new':
                // Clear the row id and data in the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState('com_menus.edit.item.data', null);
                $app->setUserState('com_menus.edit.item.type', null);
                $app->setUserState('com_menus.edit.item.link', null);
                $app->setUserState('com_menus.edit.item.menutype', $model->getState('item.menutype'));

                // Redirect back to the edit screen.
                $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend(), false));
                break;

            default:
                // Clear the row id and data in the session.
                $this->releaseEditId($context, $recordId);
                $app->setUserState('com_menus.edit.item.data', null);
                $app->setUserState('com_menus.edit.item.type', null);
                $app->setUserState('com_menus.edit.item.link', null);

                // Redirect to the list screen.
                $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));
                break;
        }

        return true;
    }

    public function ajaxSavePropertiesComponent()
    {
        $app = JFactory::getApplication();
        $form = $app->input->get('jform', array(), 'array');
        $form['params']=json_encode($form['params']);
        JTable::addIncludePath(JPATH_ROOT . '/components/com_menus/tables');
        $tableMenuItem = JTable::getInstance('Menu', 'MenusTable');
        $tableMenuItem->load($form['id']);
        $tableMenuItem->bind($form);


        $result = new stdClass();
        $result->e = 0;
        $result->m = "save success";

        if (!$tableMenuItem->store()) {
            $result->e = 1;
            $result->m = $tableMenuItem->getError();
            echo json_encode($result);
            die;
        }
        echo json_encode($result);
        die;

    }

    public function aJaxInsertComponent()
    {

        $data = array();
        $app = JFactory::getApplication();
        $modelItem = $this->getModel('Item', '', array());
        $menu_item = $app->input->getInt('menuItemActiveId', 0);
        $component = $app->input->getString('item_component', '');
        $data['request']['option'] = $component;
        $view = $app->input->getString('item_view', '');
        $layout = $app->input->getString('item_layout', 'default');
        $layout=strtolower($layout);
        $layout=str_replace('.xml','',$layout);
        $data['request']['view'] = $view;
        if($layout!='default')
        {
            $data['request']['layout'] = $layout;
        }
        $block_id = $app->input->getInt('block_id', 0);

        // Populate the row id from the session.
        $data['id'] = $menu_item;
        $data['language'] = '*';
        $data['published'] = 1;

        $data['link'] = 'index.php?' . urldecode(http_build_query($data['request'], '', '&'));
        $tableMenuItem = JTable::getInstance('menu', 'JTable');
        $tableMenuItem->load($menu_item);
        $tableMenuItem->bind($data);
        // Attempt to save the data.
        if (!$tableMenuItem->store()) {

            // Redirect back to the edit screen.
            echo JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $modelItem->getError());
            die;
        }
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('Position', 'JTable');
        $tablePosition->load($block_id);
        $tablePosition->position = 'position-component';
        $tablePosition->store();
        $fileMainComponent = substr($component, 4);
        $pathComponent = JPATH_ROOT . "/components/$component/$fileMainComponent.php";
        $htmlReturn = array();
        $app->input->set('option', $component);
        $app->input->set('view', $view);
        $app->input->set('Itemid', $menu_item);
        $app->input->set('layout', 'default');
        $link = "index.php?option=$component&view=$view&Itemid=$menu_item&tmpl=contentcomponent";
        $content = file_get_contents(JUri::root() . $link);
        $htmlReturn['componentContent'] = $content;
        echo json_encode($htmlReturn);
        die;
    }

    /**
     * Sets the type of the menu item currently being edited.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function setType()
    {
        $app = JFactory::getApplication();

        // Get the posted values from the request.
        $data = $this->input->post->get('jform', array(), 'array');

        // Get the type.
        $type = $data['type'];

        $type = json_decode(base64_decode($type));
        $title = isset($type->title) ? $type->title : null;
        $recordId = isset($type->id) ? $type->id : 0;
        $backend = $type->backend;
        $specialTypes = array('alias', 'separator', 'url', 'heading');
        if (!in_array($title, $specialTypes)) {
            $title = 'component';
        }

        $app->setUserState('com_menus.edit.item.type', $title);
        if ($title == 'component') {
            if (isset($type->request)) {
                $component = JComponentHelper::getComponent($type->request->option);
                $data['component_id'] = $component->id;

                $app->setUserState('com_menus.edit.item.link', ($backend ? 'administrator/' : '') . 'index.php?' . JUri::buildQuery((array)$type->request));
            }
        } // If the type is alias you just need the item id from the menu item referenced.
        elseif ($title == 'alias') {
            $app->setUserState('com_menus.edit.item.link', 'index.php?Itemid=');
        }

        unset($data['request']);
        $data['type'] = $title;
        if ($this->input->get('fieldtype') == 'type') {
            $data['link'] = $app->getUserState('com_menus.edit.item.link');
        }

        //Save the data in the session.
        $app->setUserState('com_menus.edit.item.data', $data);

        $this->type = $type;
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
    }

    function aJaxSetType()
    {

        $app = JFactory::getApplication();
        $modelItem = $this->getModel();
        // Get the posted values from the request.

        // Get the type.
        $type = $app->input->getString('type', '');

        $type = json_decode(base64_decode($type));
        $title = isset($type->title) ? $type->title : null;
        $recordId = isset($type->id) ? $type->id : 0;
        $app->input->set('id', $recordId);
        $specialTypes = array('alias', 'separator', 'url', 'heading');
        if (!in_array($title, $specialTypes)) {
            $title = 'component';
        }

        $modelItem->setState('item.id', $recordId);
        $modelItem->setState('item.type', $title);
        if ($title == 'component') {
            if (isset($type->request)) {
                $component = JComponentHelper::getComponent($type->request->option);
                $modelItem->setState('item.link', 'index.php?' . JUri::buildQuery((array)$type->request));
            }
        } // If the type is alias you just need the item id from the menu item referenced.
        elseif ($title == 'alias') {
            $modelItem->setState('item.link', 'index.php?Itemid=');

        }

        $data['type'] = $title;
        if ($this->input->get('fieldtype') == 'type') {
            $data['link'] = $app->getUserState('com_menus.edit.item.link');
        }

        //Save the data in the session.
        $app->setUserState('com_menus.edit.item.data', $data);

        $this->type = $type;


        $view = &$this->getView('item', 'html', 'MenusView');

        $view->setModel($modelItem, true);
        $view->setLayout('edit_type');
        ob_start();
        $view->display();
        $respone_array = array();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.item-type',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;

    }

    /**
     * Proxy for getModel
     * @since   1.6
     */
    public function getModel($name = 'Item', $prefix = 'MenusModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    /**
     * Gets the parent items of the menu location currently.
     *
     * @return  void
     *
     * @since   3.2
     */
    function getParentItem()
    {
        $app = JFactory::getApplication();

        $menutype = $this->input->get->get('menutype');

        $model = $this->getModel('Items', '', array());
        $model->setState('filter.menutype', $menutype);
        $model->setState('list.select', 'a.id, a.title, a.level');

        $results = $model->getItems();

        // Pad the option text with spaces using depth level as a multiplier.
        for ($i = 0, $n = count($results); $i < $n; $i++) {
            $results[$i]->title = str_repeat('- ', $results[$i]->level) . $results[$i]->title;
        }

        // Output a JSON object
        echo json_encode($results);

        $app->close();
    }

}

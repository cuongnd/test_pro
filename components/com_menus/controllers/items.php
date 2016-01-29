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
class MenusControllerItems extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unsetDefault',	'setDefault');
	}

	/**
	 * Proxy for getModel
	 * @since   1.6
	 */
	public function getModel($name = 'Item', $prefix = 'MenusModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
	 public function ajax_load_menu_page(){
		 $view = &$this->getView('items', 'html', 'MenusView');
		 $app = JFactory::getApplication();
		 $input = $app->input;
		 $respone_array = array();
		 ob_start();
		 JRequest::setVar('layout', 'default');
		 JRequest::setVar('tpl', 'loadmenupage');

		 $view->display();
		 $contents = ob_get_clean();
		 $respone_array[] = array(
			 'key' => '.menu_page'
		 , 'contents' => $contents
		 );
		 echo json_encode($respone_array);
		 exit();
	 }

	/**
	 * Rebuild the nested set tree.
	 *
	 * @return  bool	False on failure or error, true on success.
	 * @since   1.6
	 */
	public function rebuild()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$this->setRedirect('index.php?option=com_menus&view=items');

		$model = $this->getModel();
        $app=JFactory::getApplication();
        $post=$app->input->getArray($_POST);
        $menu_type_id=$post['filter']['menu_type_id'];
		if ($model->rebuild(array($menu_type_id)))
		{
			// Reorder succeeded.
			$this->setMessage(JText::_('COM_MENUS_ITEMS_REBUILD_SUCCESS'));
			return true;
		}
		else
		{
			// Rebuild failed.
			$this->setMessage(JText::sprintf('COM_MENUS_ITEMS_REBUILD_FAILED'));
			return false;
		}
	}
	function aJaxGetListMenuByMenuTypeId()
	{

	}
    public function ajaxSaveForm()
    {

        // Check for request forgeries
        //JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $pks = $this->input->get->get('cid', array(), 'array');
        JArrayHelper::toInteger($pks);

        try {
            if (empty($pks))
            {
                $return=array(
                    JText::_('COM_MODULES_ERROR_NO_MODULES_SELECTED')
                );
                die(json_encode($return));
            }
            $model = $this->getModel();
            $model->ajaxSaveForm($pks);
            $return=array(
                JText::plural('COM_MODULES_N_MODULES_DUPLICATED', count($pks))
            );
            die(json_encode($return));
        } catch (Exception $e)
        {
            $return=array(
                $e->getMessage()
            );
            die(json_encode($return));

        }
        $return=array(
            'ok'
        );
        die(json_encode($return));

    }



	/**
	 * Save the manual order inputs from the menu items list view
	 * 
	 * @return      void
	 * 
	 * @see         JControllerAdmin::saveorder()
	 * @deprecated  4.0
	 */
	public function saveorder()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		JLog::add('MenusControllerItems::saveorder() is deprecated. Function will be removed in 4.0', JLog::WARNING, 'deprecated');

		// Get the arrays from the Request
		$order = $this->input->post->get('order', null, 'array');
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		// Make sure something has changed
		if (!($order === $originalOrder))
		{
			parent::saveorder();
		}
		else
		{
			// Nothing to reorder
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
			return true;
		}
	}

	/**
	 * Method to set the home property for a list of items
	 *
	 * @since   1.6
	 */
	public function setDefault()
	{
		// Check for request forgeries
		JSession::checkToken('request') or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid   = $this->input->get('cid', array(), 'array');
		$data  = array('setDefault' => 1, 'unsetDefault' => 0);
		$task  = $this->getTask();
		$value = JArrayHelper::getValue($data, $task, 0, 'int');

		if (empty($cid))
		{
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);

			// Publish the items.
			if (!$model->setHome($cid, $value))
			{
				JError::raiseWarning(500, $model->getError());
			} else {
				if ($value == 1)
				{
					$ntext = 'COM_MENUS_ITEMS_SET_HOME';
				}
				else {
					$ntext = 'COM_MENUS_ITEMS_UNSET_HOME';
				}
				$this->setMessage(JText::plural($ntext, count($cid)));
			}
		}

		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
	}
}

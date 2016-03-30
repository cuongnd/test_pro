<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gianhang
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * gianhang list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_gianhang
 * @since       1.6
 */
class gianhangControllerdomains extends JControllerAdmin
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'extension', $prefix = 'gianhangModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
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
                    JText::_('com_gianhang_ERROR_NO_gianhang_SELECTED')
                );
                die(json_encode($return));
            }
            $model = $this->getModel();
            $model->ajaxSaveForm($pks);
            $return=array(
                JText::plural('com_gianhang_N_gianhang_DUPLICATED', count($pks))
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
    public function ajax_load_component(){
        $view = &$this->getView('gianhang', 'html', 'gianhangView');
        $app = JFactory::getApplication();
        $input = $app->input;
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'default');
        JRequest::setVar('tpl', 'loadcomponent');

        $view->display();
        $contents = ob_get_clean();
        $respone_array[] = array(
            'key' => '.load_component'
        , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    /**
     * Method to clone an existing module.
     * @since   1.6
     */
    function quick_assign_website()
    {
        $app=JFactory::getApplication();
        $input=JFactory::getApplication()->input;
        $pks=$input->get('cid',array(),'array');
        JArrayHelper::toInteger($pks);
        $copy=$input->get('copy',0,'int');
        $website_id=$input->get('website_id',0,'int');
        if($copy)
        {
            try {
                if (empty($pks))
                {
                    throw new Exception(JText::_('com_gianhang_ERROR_NO_COMPONENT_SELECTED'));
                }
                $model = $this->getModel();
                $model->duplicateAndAssign($pks,$website_id);
                $this->setMessage(JText::plural('com_gianhang_N_MODULE_DUPLICATED', count($pks)));
            } catch (Exception $e)
            {
                JError::raiseWarning(500, $e->getMessage());
            }
            $this->setRedirect(JRoute::_('index.php?option=com_gianhang'));
        }
        else {
            try {
                if (empty($pks)) {
                    throw new Exception(JText::_('com_gianhang_ERROR_NO_MODULE_SELECTED'));
                }
                $model = $this->getModel();
                $model->quick_assign_website($pks, $website_id);
                $this->setMessage(JText::plural('com_gianhang_N_QUICK_ASSIGN', count($pks)));
            } catch (Exception $e) {
                JError::raiseWarning(500, $e->getMessage());
            }

            $this->setRedirect(JRoute::_('index.php?option=com_gianhang'));
        }
    }




}
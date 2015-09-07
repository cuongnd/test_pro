<?php
/**
 * @package     Joomla.Administrator
 * @subpackage
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Bustrips list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage
 * @since       1.6
 */
class BookproControllerbustrips extends JControllerAdmin
{
    /**
     * Method to clone an existing module.
     * @since   1.6
     */
    public function duplicate()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $pks = $this->input->post->get('cid', array(), 'array');
        JArrayHelper::toInteger($pks);

        try {
            if (empty($pks))
            {
                throw new Exception(JText::_('COM_BOOKPRO_ERROR_NO_BUSTRIP_SELECTED'));
            }
            $model = $this->getModel();
            $model->duplicate($pks);
            $this->setMessage(JText::plural('COM_BOOKPRO_N_BUSTRIP_DUPLICATED', count($pks)));
        } catch (Exception $e)
        {
            JError::raiseWarning(500, $e->getMessage());
        }

        $this->setRedirect('index.php?option=com_bookpro&view=bustrips');
    }

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
	public function getModel($name = 'Bustrip', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}

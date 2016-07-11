<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * cpanel list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.6
 */
class ProductsControllerProductCategories extends JControllerAdmin
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
	public function getModel($name = 'productcategory', $prefix = 'productsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
    public function rebuild()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel();

        if ($model->rebuild())
        {
            // Rebuild succeeded.
            $this->setMessage(JText::_('COM_CATEGORIES_REBUILD_SUCCESS'));

            return true;
        }
        else
        {
            // Rebuild failed.
            $this->setMessage(JText::_('COM_CATEGORIES_REBUILD_FAILURE'));

            return false;
        }
    }

    /**
     * Method to clone an existing module.
     * @since   1.6
     */




}

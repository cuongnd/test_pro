<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * domain controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminControllerWebsite extends JControllerForm
{
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
    public function __construct($config = array())
    {
        parent::__construct($config);
// An article edit form can come from the articles or featured view.
        // Adjust the redirect view on the value of 'return' in the request.
        $this->view_list = 'websites';
        $this->view_item = 'website';
    }

    public function getModel($name = 'website', $prefix = 'supperadminModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }


}

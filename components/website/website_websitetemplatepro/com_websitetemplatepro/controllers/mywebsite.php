<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * mywebsite controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.6
 */
class websitetemplateproControllermywebsite extends JControllerForm
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
        $this->view_list = 'listmywebsite';
        $this->view_item = 'mywebsite';
    }
    public function add()
    {
        $this->setRedirect('index.php?option=com_website&view=website');
        return;
        return parent::add(); // TODO: Change the autogenerated stub
    }

    protected function allowAdd($data = array())
    {
        return true;
    }

    public function getModel($name = 'mywebsite', $prefix = 'websitetemplateproModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }



}

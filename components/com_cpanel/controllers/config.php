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
 * config controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.6
 */
class cpanelControllerconfig extends JControllerForm
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
        $this->view_list = 'configs';
        $this->view_item = 'config';
    }
    
    public function getModel($name = 'config', $prefix = 'cpanelModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
    public function ajax_set_request_update_website()
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__website AS website')
            ->where('website.copy_from='.(int)$website->website_id)
            ->set('website.request_update=1')
            ;
        $db->setQuery($query);
        $ok=$db->execute();
        $response=new stdClass();
        $response->e=0;
        if(!$ok)
        {
            $response->e=1;
            $response->m=$db->getErrorMsg();
        }
        echo json_encode($response);
        die;
    }


}

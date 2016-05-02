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
class SupperadminControllerotheradmintool extends JControllerForm
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

    public function fix_menu()
    {
        require_once JPATH_ROOT.'/components/com_menus/helpers/menus.php';
        MenusHelperFrontEnd::remove_all_menu_not_exists_menu_type();
        $response=new stdClass();
        $response->e=0;
        echo json_encode($response);
        die;
    }
    public function fix_block()
    {
        require_once JPATH_ROOT.'/components/com_utility/helper/block_helper.php';
        block_helper::fix_block();
        $response=new stdClass();
        $response->e=0;
        echo json_encode($response);
        die;
    }



}

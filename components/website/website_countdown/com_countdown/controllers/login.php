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
class CountdownControllerLogin extends JControllerForm
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
    public function login(){

        $app=JFactory::getApplication();
        $input=$app->input;
        $jform=$input->get('jform',array(),'array');
/*        echo json_encode($input->getArray());
        die;*/
        $user=JFactory::getUser();
        $menu=$app->getMenu();
        $menu_default=$menu->getDefault();
        if($user->id)
        {

            $app->redirect(JUri::root().$menu_default->link."&Itemid=".$menu_default->id);
            return false;
        }
        $website=JFactory::getWebsite();
        $your_phone=$jform['your_phone'];
        $data=array();
        require_once JPATH_ROOT.'/components/com_users/helpers/groups.php';

        $data['name']  = $your_phone;
        $data['username']  =JUtility::gen_random_string(6). $your_phone;
        $data['email']  = JUtility::gen_random_string(6)."@mail.com";
        $system = GroupsHelper::get_user_group_id_default();

        $data['groups'][]  = $system;
        $password='123456';
        $data['password']  = $password;
        $data['block'] = 0;
        $data['website_id'] = $website->website_id;

        $user = new JUser;
        $login_item = JFactory::get_page_login();
        if (!$user->bind($data))
        {

            $app->redirect(JUri::root().$login_item->link."Itemid=".$login_item->id);
            return false;
        }
        // Load the users plugin group.
        JPluginHelper::importPlugin('user');
        // Store the data.
        if (!$user->save())
        {

            $login_item = JFactory::get_page_login();
            echo $user->getError();
            die;
            $app->redirect(JUri::root().$login_item->link);
            return false;
        }
        $credentials = array();
        $credentials['username']  = $data['username'];
        $credentials['password']  = $password;
        $credentials['secretkey'] = JSession::getFormToken();
        $options=array();
        $options['remember'] = true;
        // Perform the log in.

        if (true === $app->login($credentials, $options))
        {
            // Success
            if ($options['remember'] = true)
            {
                $app->setUserState('rememberLogin', true);
            }

            $user=JFactory::getUser();
            $app->redirect(JUri::root().$menu_default->link."&Itemid=".$menu_default->id."&android_ses_id=".session_id());


            return true;
        }
        else
        {

            // Login failed !
            $data['remember'] = (int) $options['remember'];
            $app->setUserState('users.login.form.data', $data);
            $app->redirect(JUri::root().$login_item->link."Itemid=".$login_item->id);
            return false;
        }
    }


}

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
 * The Menu List Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusControllerMenutypes extends JControllerAdmin
{

    function aJaxGetMenuItemType()
    {
        JModelLegacy::addIncludePath(JPATH_ROOT.'/administrator/components/com_menus/models');
        $view = &$this->getView('menutypes', 'html', 'MenusView');
        $view->setModel( $this->getModel(), true );
        $respone_array=array();
        ob_start();
        $view->display();
        $contents = ob_get_clean();
        $respone_array[] = array(
            'key' => '#modal_menu_item_type .modal-body',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }
    /**
     * Proxy for getModel
     * @since   1.6
     */
    public function getModel($name = 'menutypes', $prefix = 'MenusModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}

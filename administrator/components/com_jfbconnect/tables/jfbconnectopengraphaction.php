<?php

/**
 * @package		JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

class TableJFBConnectOpenGraphAction extends JTable
{
	var $id = null;
	var $plugin = null;
    var $system_name = null;
    var $display_name = null;
    var $action = null;
    var $fb_built_in = false;
    var $can_disable = true;
    var $params = null;
	var $published = 0;
    var $created = null;
    var $modified = null;

	function TableJFBConnectOpenGraphAction(&$db)
	{
		parent::__construct('#__opengraph_action', 'id', $db);
	}
}
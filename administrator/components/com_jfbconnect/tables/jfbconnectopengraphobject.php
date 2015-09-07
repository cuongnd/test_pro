<?php

/**
 * @package		JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

class TableJFBConnectOpenGraphObject extends JTable
{
	var $id = null;
	var $plugin = null;
    var $system_name = null;
    var $display_name = null;
    var $type = null;
	var $published = 0;
    var $fb_built_in = false;
    var $params = "";
    var $created = null;
    var $modified = null;

	function TableJFBConnectOpenGraphObject(&$db)
	{
		parent::__construct('#__opengraph_object', 'id', $db);
	}
}
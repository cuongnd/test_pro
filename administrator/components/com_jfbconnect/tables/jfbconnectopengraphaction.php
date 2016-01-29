<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
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